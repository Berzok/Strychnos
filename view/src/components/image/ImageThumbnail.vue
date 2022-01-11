<template>
  <router-link :to="{ name: 'view', params: { id: image.id}}">
    <img :src="getImageSource" :alt="image.filename" class="img-fluid thumbnail"/>
  </router-link>
</template>

<script lang="js">
import {computed, defineComponent} from 'vue';
import {useStore as useUserStore} from './../../store/modules/user';

export default defineComponent({
    name: 'ImageThumbnail',
    props: {
        image: {}
    },
    computed: {
        getImageSource(){
            return process.env.VITE_API_URL + '/image/file/' + this.image.id
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

.thumbnail {
    border: 1px solid thistle;
    border-radius: 0.25rem;
    max-height: 300px;
}

</style>