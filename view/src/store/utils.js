import {defineStore, acceptHMRUpdate} from 'pinia';
import axios from "axios";

const useStore = defineStore('utils', {
    state: () => ({
        /** @type {'all' | 'finished' | 'unfinished'} */
        filter: 'all',
        // type will be automatically inferred to number
        images: [],
        currentTags: [],
        tagsToSearch: [],
        tags: [],
        current_page: null,
        imagesCount: null
    }),
    getters: {
        getTags(){
            return this.tags;
        },
        getImagesCount(){
            return this.imagesCount;
        },
        queryfiedTags(){
            const tagsName = this.tagsToSearch.map(v => v.name);
            return tagsName.join('+');
        }
    },
    actions: {
        refreshTags(){

        },
        async getAllTags() {
            const response = await axios.get('/tags').then(response => response.data);
            console.dir(response);
            return response;
        },
        populate(tags){
            this.tags = tags;
        },
        async fetchImages(limit, offset){
            const query = this.queryfiedTags;
            axios.get('/images', {
                headers: {
                    'Wait': true
                },
                params: {
                    tags: query,
                    limit: limit,
                    offset: offset
                }
            }).then((response, error) => {
                this.tagsToSearch = [];
                this.images = response.data;
                this.imagesCount = this.images.length;
                let tags = [];
                this.images.forEach((i) => {
                    tags = Array.prototype.concat(tags, i.tags);
                });
                tags.forEach((element) => {
                    if(!this.currentTags.some(value => value.id === element.id)){
                        this.currentTags.push(element);
                    }
                });
            });
        }
    },
});

// make sure to pass the right store definition, `useAuth` in this case.
if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useStore, import.meta.hot))
}

export {useStore};