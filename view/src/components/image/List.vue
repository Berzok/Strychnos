<template>
  <div class="text-center">

    <DataView :value="this.images" :layout="'grid'" :paginator="true" :rows="20" class="justify-content-evenly">
      <template #grid="slotProps">
        <ImageThumbnail :image="slotProps.data" class="grid-item mb-4"/>
      </template>
    </DataView>

  </div>
</template>

<script lang="js">
import {useToast} from 'vue-toastification';
import {defineComponent} from 'vue';
import axios from 'axios';
import {useI18n} from 'vue-i18n';
import DataView from 'primevue/dataview';
import ImageThumbnail from "./ImageThumbnail";

export default defineComponent({
    name: 'List',
    components: {
        ImageThumbnail,
        DataView
    },
    setup() {
        // Get toast interface
        const toast = useToast();
        const {t, locale} = useI18n();
        return {toast, t, locale};
    },
    data() {
        return {
            images: []
        };
    },
    mounted() {
        axios.get('/images', {
            headers: {
                'Wait': true
            }
        }).then((response, error) => {
            this.images = response.data;
        });
    },
    methods: {
    },
});
</script>

<style scoped>

.grid-item{
    flex: 0 0 auto;
    width: 18%;
}

::v-deep(.p-dataview-content){
    background: transparent !important;
}
::v-deep(.p-grid) {
    justify-content: space-evenly;
}
</style>
