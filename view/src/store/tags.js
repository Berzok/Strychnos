import {defineStore, acceptHMRUpdate} from 'pinia';
import axios from "axios";

const useStore = defineStore('tags', {
    state: () => ({
        /** @type {'all' | 'finished' | 'unfinished'} */
        filter: 'all',
        // type will be automatically inferred to number
        token: '',
        logged: false,
        tags: []
    }),
    getters: {
        getToken(){
            return this.token;
        },
        getTags(){
            return this.tags;
        },
        /**
         * Check if a user is logged;
         * @param state
         * @returns {boolean|*}
         */
        isLogged(state){
            return state.logged;
        }
    },
    actions: {
        checkToken(){
            return axios.post('/token/verify', {'token': this.token}).then((response, error) => {
                return response.data;
            });
        },
        populate(tags){
            this.tags = tags;
        }
    },
});

// make sure to pass the right store definition, `useAuth` in this case.
if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useStore, import.meta.hot))
}

export {useStore};