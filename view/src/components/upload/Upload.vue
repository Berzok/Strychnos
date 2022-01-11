<template>
  <div class="d-flex flex-fill flex-column">

    <h1 class="text-center mb-5">Upload</h1>

    <div class="col">

      <form id="form_upload" class="d-flex flex-row text-start">

        <div class="col-3 ms-5">
          <label for="source" class="form-label">Source: </label>
          <div class="input-group mb-3">
            <input id="source" v-model="this.source" name="source" class="form-control" type="text">
            <button @click="checkSource" class="btn user-select-none" type="button">
              <span class="fas fa-sync"></span>
            </button>
          </div>

          <label for="name_artist" class="form-label">Artiste: </label>
          <div class="input-group mb-3">
            <input v-model="this.artist.name"
                   id="name_artist" name="artiste" class="form-control" type="text" disabled>
          </div>

          <label for="url" class="form-label">Lien direct: </label>
          <div class="input-group mb-3">
            <input v-model="this.url"
                   id="url" name="url" class="form-control user-select-none" type="url" readonly>
          </div>

          <label for="tags" class="form-label">Tags: </label>
          <div class="input-group mb-3">
            <textarea id="tags" name="tags" class="form-control" type="text"></textarea>
          </div>


          <div class="d-flex">
            <Button label="Valider" icon="pi pi-check"
                    @click="upload"
                    class="p-button-success btn-outline-warning w-75 mt-5"/>
            <Button label="Annuler" icon="pi pi-times"
                    @click="resetForm"
                    class="p-button-warning btn-outline-warning w-75 mt-5"/>
          </div>

        </div>

        <div class="col-8 align-self-center">
          <div class="d-flex flex-column">
            <img id="image_upload" class="img-fluid border-gold mx-auto" :src="this.base64.thumbnail" alt="">
            <input id="file_input" name="image" class="align-self-center" type="file">
          </div>
        </div>

      </form>
    </div>
  </div>

</template>

<script lang="js">
import {defineComponent} from 'vue';
import axios from "axios";
import {useToast} from "vue-toastification";
import {useI18n} from "vue-i18n";
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Divider from 'primevue/divider';

export default defineComponent({
    name: 'Upload',
    components: {
        Button,
        Dialog,
        Divider
    },
    computed: {
        getLocalSource() {
            if (this.image.id) {
                return process.env.VITE_API_URL + '/image/file/' + this.image.id
            }
            return '';
        },
        extension(){
            let array = this.url.split('.');
            return '.' + array.pop();
        }
    },
    data() {
        return {
            artist: {},
            base64: {},
            image: null,
            url: null,
            large_image_url: null,
            source: null,
            modalPreview: null
        }
    },
    methods: {
        createPreview(node_selector, file) {
            let imageSrc = URL.createObjectURL(file);
            //$(node_selector).css('background-image', 'url(' + imageSrc + ')');
            $(node_selector).get(0).src = imageSrc;
            document.querySelector('#modal_preview_image').src = imageSrc;
            /*
            let node = document.querySelector(node_selector);
            node.src = URL.createObjectURL(file);
            node.onload = function() {
                URL.revokeObjectURL(node.src) // free memory
            }
            */
        },
        resetForm(){

        },
        createThumbnail(data, size) {
            let dataURL = 'data:image/' + data.type + ';base64,' + data.base64;
            if (size === 'large') {
                this.base64.thumbnail = dataURL;
            } else {
                this.base64.preview = dataURL;
            }
            //$('#check_source').find('span').removeClass('fa-spin');
        },
        checkSource() {
            let id_post = this.source.split('/').pop();
            this.getPixivDetails(id_post, (data) => {
                this.url = data.meta_single_page.original_image_url;
                this.large_image_url = data.image_urls.large;
                this.artist = data.user;
                this.image = data.id;

                this.getPixivThumbnail(this.large_image_url, this.large_image_url.split('.').pop(), 'large', this.createThumbnail);
                this.getPixivThumbnail(this.url, this.url.split('.').pop(), 'big', this.createThumbnail);
            });
        },
        /**
         * Récupère les informations détaillées d'une page pixiv à partir d'un id
         * @param id_post
         * @param callback
         */
        getPixivDetails(id_post, callback) {
            axios.post('/pixiv/get/' + id_post).then((response, error) => {
                callback(response.data);
            });
        },
        getPixivThumbnail(url, type, size, callback) {
            axios.post('/pixiv/image_b64',
                {
                    url: url
                },
                {
                    headers: {
                        'Wait': true
                    }
                }).then((response, error) => {
                callback(response.data, size);
            });
        },
        async upload(){
            await axios.post('/image/create', {
                artist: this.artist,
                filename: this.image,
                extension: this.extension,
                source: this.source,
                url: this.url,
            },
                {
                    headers: {
                        'Wait': true
                    }
                }).then((response, error) => {
                console.dir(response);
            });
            await axios.post('/pixiv/download', {
                url: this.large_image_url,
                filename: this.image,
                extension: this.extension
            },
                {
                    headers: {
                        'Wait': true
                    }
                }
            );
            this.toast.success('Upload réussi');
        }
    },
    async mounted() {
        /*
        axios.post('/explore/pixiv_original', {image: this.image}).then((response) => {
            const data = response.data;
            const type = data.data;
            const base64 = data.base64;
            this.oSource = 'data:' + type + ';base64,' + base64;
            return true;
        });
         */

    },
    setup() {
        // Get toast interface
        const toast = useToast();
        const {t, locale} = useI18n();
        return {toast, t, locale};
    },
});
</script>

<style scoped>

form{
    color: cornflowerblue;
}

</style>