import {defineStore, acceptHMRUpdate} from 'pinia';
import axios from "axios";

const useImageStore = defineStore('image', {
    state: () => ({
        ///** @type {'all' | 'finished' | 'unfinished'} */
        // type will be automatically inferred to object
        image: {}
    }),
    getters: {
        getTags(){
            return this.image.tags;
        },
        getImage(){
            return this.image;
        }
    },
    actions: {
    },
});

// make sure to pass the right store definition, `useAuth` in this case.
if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useImageStore, import.meta.hot))
}

export {useImageStore};