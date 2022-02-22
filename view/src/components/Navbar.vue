<template>
  <div id="sidebar" class="">

    <div class="my-2 mx-3">
      <div class="mb-2">
        <label for="tag_input" class="form-label col-8">Tags to search</label>
        <button class="btn btn-primary col-4"
                @click="this.searchTag">
          <span class="fas fa-search"></span>
        </button>
      </div>
      <input id="tag_input"
             autofocus
             v-model="this.searchTerm"
             @input="matchTag($event, 'oui')"
             @keydown.enter="searchTag($event)"
             class="awesomplete w-100"/>
      <ul v-if="this.matchedTags.length">
        <li v-for="t in this.matchedTags" :key="t.name"
            @click="this.selectTag(t)">
          {{ t.name }}
        </li>
      </ul>
    </div>

    <div class="text-light text-start">
      <ul class="list-group list-group-flush ps-2">

        <template v-for="type in this.types" :key="type">
          <li class="list-group-item mt-2">
            <span>
              {{ type.name }}
            </span>
          </li>
          <li v-for="tag in this.tagsByType(type.name.toLowerCase())" :key="tag" :class="'list-group-item tag-' + type.name.toLowerCase()">
            <span>?</span>
            {{ tag.name }}
          </li>
        </template>
      </ul>
    </div>

  </div>
</template>

<script lang="js">
import {computed, defineComponent} from 'vue';
import {useStore as useUserStore} from './../store/modules/user';
import AutoComplete from 'primevue/autocomplete';
import Awesomplete from 'awesomplete';
import 'awesomplete/awesomplete.theme.css';
import axios from "axios";
import {useStore as useUtilsStore} from "@/store/utils";
import {useImageStore} from "@/store/image";
import {mapActions, mapState} from 'pinia';

export default defineComponent({
    name: 'Navbar',
    components: {
        AutoComplete,
    },
    computed: {
        tagsName() {
            return this.tags.map(v => {
                return v.name;
            })
        },
        selectedTags() {
            let tab = this.searchTerm.split(' ');
            if (tab[tab.length - 1] === ' ') {
                tab.pop();
            }
            return tab;
        }
    },
    data() {
        return {
            aw: {},
            filteredTags: [],
            matchedTags: [],
            searchTerm: '',
            tags: [],
            types: []
        }
    },
    methods: {
        tagsByType(type = null){
            if(!type){
                return this.tags;
            } else{
                if(this.imageStore.image.length){
                    console.dir(this.imageStore.image);
                    //this.tags = this.imageStore.getTags;
                }
                return this.utilsStore.currentTags.filter(v => {
                    return v.type.name.toLowerCase() === type;
                });
            }
        },
        searchTag() {
            this.utilsStore.tagsToSearch = this.filteredTags;
            this.utilsStore.fetchImages();
        },
        selectTag(tag) {
            this.filteredTags.push(tag);
            this.matchedTags = [];

            //We add the not yet written portion of the tag name
            let before = this.searchTerm.match(/^(\S+\s)*|/)[0];
            this.searchTerm = before + tag.name + ' ';

            //const startIndex = tag.name.indexOf(this.searchTerm) + this.searchTerm.length;
            //this.searchTerm += tag.name.slice(startIndex) + ' ';
        },
        matchTag(event) {
            let query = this.searchTerm.split(' ');

            axios.get('/tags/match', {
                params: {
                    q: query.pop()
                }
            }).then((response, error) => {
                this.matchedTags = response.data.map(v => {
                    return v;
                });
                /*
                this.aw.list = response.data.map(v => {
                    return v.name;
                });
                */
            });
            return null;
        },
    },
    async mounted() {
        const store = useUtilsStore();

        axios.get('/tags').then((response, error) => {
            this.tags = response.data;
            store.populate(response.data);
        });

        axios.get('/tags/types').then((response, error) => {
            this.types = response.data;
        });


        let input = document.getElementById('tag_input');
        // let aw = new Awesomplete(input, {minChars: 1, list: this.tagsName});
        this.aw = new Awesomplete(input, {minChars: 1});

        this.aw.filter = function (text, input) {
            return Awesomplete.FILTER_CONTAINS(text, input.match(/^(\S+\s)*|/)[0])
            //return Awesomplete.FILTER_CONTAINS(text, input.match(/^(\S+\s)*|/)[0]);
        }

        this.aw.item = function (text, input, item_id) {
            let html = input.trim() === "" ? text : text.replace(RegExp(input.trim(), "gi"), '<span>$&</span>');
            let liNode = document.createElement('li');
            liNode.id = 'awesomplete_list_' + this.count + '_item_' + item_id;
            liNode.role = "option";
            liNode.setAttribute('aria-selected', false);
            liNode.innerHTML = html;
            return liNode;
        };

        /**
         * Controls how the user’s selection replaces the user’s input.
         * For example, this is useful if you want the selection to only partially replace the user’s input.
         * @param {String} text
         */
        this.aw.replace = function (text) {
            let before = this.input.value.match(/^(\S+\s)*|/)[0];
            this.input.value = before + text + ' ';
        }

        this.aw.input.addEventListener('awesomplete-selectcomplete', (event) => {
            console.dir(event);
            return 'oui';
        });
    },
    setup() {
        const store = useUserStore();
        const utilsStore = useUtilsStore();
        const imageStore = useImageStore();
        const isLoggedIn = computed(() => store.getters.isLogged);
        return {isLoggedIn, utilsStore, imageStore};
    },
});
</script>

<style scoped>
#sidebar {
    border-right: 1px dashed grey;
    color: thistle;
    min-height: calc(100vh - 140px);
    min-width: 210px !important;
    max-width: 210px !important;
    font-family: "Lato", Helvetica, "Roboto", Arial, sans-serif;
}

::v-deep(.matched-part) {
    background-color: moccasin;
}

::v-deep(.p-autocomplete-token) {
    padding: 0.25rem 0.5rem !important;
}

</style>
