<template>
  <div class="flex-fill">

    <button class="btn btn-primary" @click="this.showCreateDialog()">
      <span class="fas fa-plus"></span>
      Nouveau
    </button>

    <hr />

    <DataTable :value="this.tags"
               :paginator="true"
               :rowHover="true"
               :rowClass="this.rowClass"
               :rows="10"
    >
      <Column field="name" header="Nom"></Column>
      <Column field="type.name" header="Type"></Column>
      <Column field="count" header="Nombre"></Column>
      <Column field="description" header="Description"></Column>
      <Column style="width: 120px !important;">
        <template #body="slotProps">
          <button class="btn btn-primary mr-2" @click="showEditDialog(slotProps.data)">
            <span class="fas fa-pen"></span>
          </button>
          <button class="btn btn-danger" @click="confirmDelete(slotProps.data)">
            <span class="fas fa-trash"></span>
          </button>
        </template>
      </Column>
    </DataTable>

    <Dialog v-model:visible="this.dialogCreate" :style="{width: '35%'}" header="Create new tag" :modal="true" class="p-fluid">
      <EditTag :types="this.types"></EditTag>
    </Dialog>

    <Dialog v-model:visible="this.dialogEdit" :style="{width: '35%'}" header="Edit tag" :modal="true" class="p-fluid">
      <EditTag :types="this.types" v-model="this.selectedTag"></EditTag>
    </Dialog>

  </div>
</template>

<script lang="js">
import {useToast} from 'vue-toastification';
import {defineComponent} from 'vue';
import axios from 'axios';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Dialog from 'primevue/dialog';
import EditTag from "@/components/tags/EditTag";

export default defineComponent({
    name: 'Tags',
    components: {
        Dialog,
        DataTable,
        Column,
        EditTag
    },
    data() {
        return {
            tags: [],
            categories: [],
            toast: useToast(),
            dialogCreate: false,
            dialogEdit: false,
            selectedTag: {}
        };
    },
    mounted() {
        axios.get('/tags').then((response, error) => {
            this.tags = response.data;
        });
        axios.get('/tags/types').then((response, error) => {
            this.types = response.data;
        });
    },
    methods: {
        rowClass(data){
            if(data.type){
                return 'tag-' + data.type.name.toLowerCase();
            }
        },
        showCreateDialog(){
            this.dialogCreate = true;
        },
        showEditDialog(selected){
            this.selectedTag = selected;
            this.dialogEdit = true;
        },
        confirmDelete(){

        }
    },
});
</script>

<style scoped>

img {
    width: 220px;
}
</style>
