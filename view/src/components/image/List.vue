<template>
  <div class="text-center">

    <DataView :value="this.images"
              :layout="'grid'"
              :rows="20"
              :lazy="true"
              :paginator="true"
              :first="this.page * this.size"
              :totalRecords="this.totalRecords"
              @page="onPage($event)"
              class="justify-content-evenly">
      <template #grid="slotProps">
        <ImageThumbnail :image="slotProps.data" class="grid-item mb-4"/>
      </template>
    </DataView>

  </div>
</template>

<script lang="js">
import {defineComponent} from 'vue';
import {useToast} from 'vue-toastification';
import axios from 'axios';
import {mapActions, mapState} from 'pinia';
import DataView from 'primevue/dataview';
import ImageThumbnail from "./ImageThumbnail";
import {useStore as useUtilsStore} from "@/store/utils";

export default defineComponent({
    name: 'List',
    components: {
        ImageThumbnail,
        DataView
    },
    data(){
        return {
            size: 20,
        }
    },
    setup() {
        // Get toast interface
        const toast = useToast();
        const utilsStore = useUtilsStore();
        return {toast, utilsStore};
    },
    computed: {
        images(){
            return this.utilsStore.images;
        },
        page(){
            return this.$route.params.page;
        },
        totalRecords(){
            return this.utilsStore.imagesCount;
        }
    },
    mounted() {
        this.getTotal();
        if(!this.$route.params.page){
            this.$route.params.page = 1;
        }
        //this.page = parseInt(this.$route.params.page);
        this.utilsStore.fetchImages(this.size, this.size * this.page);
    },
    methods: {
        onPage(event){
            this.utilsStore.fetchImages(this.size, this.size * event.page);
            //this.$router.push(this.$route.path + '/' + event.page);
            this.$router.push({params: {page: event.page}});
        },
        getTotal(){
            axios.get('/images/count').then((response, error) => {
                this.utilsStore.imagesCount = response.data;
                this.imagesCount = response.data;
                return response.data;
            });
        }
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
