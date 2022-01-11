<template>
  <div class="loader-overlay" v-if="isLoading">
    <div class="spinner-border loader" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</template>

<script lang="js">
import {defineComponent} from 'vue';
import {loadStore as useLoadStore} from './../store/modules/loading';

export default defineComponent({
    name: 'Spinner',
    components: {},
    computed: {
        isLoading() {
            return useLoadStore().getters.isLoading;
        },
    },
});
</script>

<style scoped>
.loader-overlay {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
}

.loader {
    height: 80px;
    width: 80px;
    position: absolute;
    left: calc(50% - 40px);
    top: calc(50% - 40px);
    opacity: 1;
    color: white;
}
</style>
