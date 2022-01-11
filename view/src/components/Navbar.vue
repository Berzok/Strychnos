<template>
  <div id="sidebar" class="">

    <div class="my-2 mx-3">
      <label for="tag_input" class="form-label">Tags to search</label>
      <input id="tag_input"
             autofocus
             @input="matchedTags($event, 'oui')"
             class="awesomplete w-100"/>
    </div>

    <div class="text-light text-start">
      <ul class="list-group list-group-flush ps-2">
        <template v-for="type in this.types" :key="type">

          <li class="list-group-item mt-2">
            <span>
              {{ type.name }}
            </span>
          </li>
          <li v-for="tag in type.tags" :key="tag" :class="'list-group-item tag-' + type.name.toLowerCase()">
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
import {useStore} from "./../store/tags";
import {useImageStore} from "./../store/image";
import {mapActions} from 'pinia';

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
        }
    },
    data() {
        return {
            aw: {},
            filteredTags: [],
            selectedTags: [],
            tags: [],
            types: [],
            primevue: false
        }
    },
    methods: {
        searchTag(event) {
            setTimeout(() => {
                if (!event.query.trim().length) {
                    this.filteredTags = [...this.tags];
                } else {
                    this.filteredTags = this.tags.filter((tag) => {
                        return tag.name.toLowerCase().startsWith(event.query.toLowerCase());
                    });
                }
            }, 250);
        },
        matchedTags(event) {
            axios.get('/tags/match', {
                params: {
                    q: event.target.value
                }
            }).then((response, error) => {
                this.aw.list = response.data.map(v => {
                    return v.name;
                });
            });
            return null;
        },
        ...mapActions(useStore, ['populate'])
    },
    async mounted() {
        const store = useStore();
        const imageStore = useImageStore();
        await axios.get('/tags').then((response, error) => {
            this.tags = response.data;
            store.populate(this.tags);
        });
        await axios.get('/tags/types').then((response, error) => {
            this.types = response.data;
        });

        let input = document.getElementById('tag_input');
        // let aw = new Awesomplete(input, {minChars: 1, list: this.tagsName});
        this.aw = new Awesomplete(input, {minChars: 1});

        this.aw.item = function (text, input, item_id) {
            let html = input.trim() === "" ? text : text.replace(RegExp(input.trim(), "gi"), '<span>$&</span>');
            let liNode = document.createElement('li');
            liNode.id = 'awesomplete_list_' + this.count + '_item_' + item_id;
            liNode.role = "option";
            liNode.setAttribute('aria-selected', false);
            liNode.innerHTML = html;
            return liNode;
        };
        this.aw.replace = function(text){
            let before = this.input.value.match(/^(\S+\s)*|/)[0];
            this.input.value = before + text + ' ';
        }
    },
    setup() {
        const store = useUserStore();
        const isLoggedIn = computed(() => store.getters.isLogged);
        return {isLoggedIn};
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

::v-deep(.matched-part){
    background-color: moccasin;
}

::v-deep(.p-autocomplete-token) {
    padding: 0.25rem 0.5rem !important;
}

</style>
