<template>
  <div class="flex-fill text-center min-vh-100">

    <br>

    <img src="/homepage.jpg" class="img-fluid w-75" alt="Safebooru">

    <div class="d-flex justify-content-center my-3">
      <router-link to="/images" class="nav-link">
        <span class="far fa-images"></span>
        Images
      </router-link>

      <router-link to="/profile" class="nav-link">
        <span class="fas fa-tags"></span>
        Tags
      </router-link>

      <router-link to="/upload" class="nav-link">
        <span class="fas fa-upload"></span>
        Upload
      </router-link>


      <router-link to="/profile" class="nav-link">
        <span class="fas fa-music"></span>
        <a href="http://radio.safebooru.org:8000/listen.pls"> Radio</a>
      </router-link>
    </div>

    <div class="w-25 mx-auto">
      <div class="input-group mb-3">
        <input id="tags_input"
               autofocus
               @input="matchedTags($event, 'oui')"
               class="awesomplete form-control w-100"/>
        <button class="btn btn-primary">
          <span class="fas fa-search"></span>
        </button>
      </div>
    </div>

    <div class="d-flex flex-column mt-5 mb-1">
      <small class="text-info">
        Serving {{ this.imagesCount }} posts
      </small>

      <p class="position-absolute bottom-0 end-0 me-2">
        <a href="https://github.com/Berzok/Strychnos" target="_blank">
          <span class="fab fa-github"></span>
          Source on Github
        </a>
      </p>

    </div>
  </div>
</template>

<script lang="js">
import {defineComponent} from 'vue';
import axios from "axios"; // @ is an alias to /src
import Awesomplete from 'awesomplete';
import 'awesomplete/awesomplete.theme.css';
import Button from 'primevue/button';

export default defineComponent({
    name: 'Home',
    components: {
        Button,
    },
    computed: {},
    created() {
        axios.get('/images/count').then((response, error) => {
            this.imagesCount = response.data;
            return response.data;
        });
    },
    data() {
        return {
            aw: {},
            filteredTags: [],
            imagesCount: null
        }
    },
    methods: {
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
    },
    mounted() {
        let input = document.getElementById('tags_input');
        // let aw = new Awesomplete(input, {minChars: 1, list: this.tagsName});
        this.aw = new Awesomplete(input, {minChars: 1});

        input.addEventListener('awesomplete-select', (e) => {
            alert(`selected ${e.text.value}`)
        });

        this.aw.item = function (text, input, item_id) {
            let html = input.trim() === "" ? text : text.replace(RegExp(input.trim(), "gi"), '<span class="bg-warning">$&</span>');
            let liNode = document.createElement('li');
            liNode.id = 'awesomplete_list_' + this.count + '_item_' + item_id;
            liNode.role = "option";
            liNode.setAttribute('aria-selected', false);
            liNode.innerHTML = html;
            return liNode;
        };
    }
});
</script>

<style scoped>
::v-deep(.awesomplete) {
    flex: 1 1 auto !important;
}

</style>