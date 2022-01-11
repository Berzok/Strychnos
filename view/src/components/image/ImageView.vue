<template>
  <title>Image unique</title>
  <div class="d-flex flex-column">

    <div class="d-flex mb-2">

      <div class="me-4">
        <button class="btn btn-warning" @click="switchSource">
          <span class="fas fa-sync"></span>
          Alternative
        </button>
      </div>

      <div>

        <div class="d-flex mb-2">
          <div class="me-2">
            <button id="expand_details" class="btn btn-primary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse_details" aria-expanded="false" aria-controls="collapse_details"
                    @click="scrollToEnd">
              <span class="fas fa-chevron-right"></span>
              Info
            </button>
          </div>
          <div class="me-2">
            <button id="expand_tags_add" class="btn btn-primary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse_tags_add" aria-expanded="false" aria-controls="collapse_tags_add"
                    @click="openModal">
              <span class="fas fa-tags"></span>
              Éditer les tags
            </button>
          </div>
        </div>


        <div id="collapse_container" class="text-start" @resize="element.scrollIntoView({behavior: 'smooth'});">

          <div id="collapse_details" class="collapse" data-bs-parent="#collapse_container">
            <dl class="row d-flex">
              <dt class="col-1">Ajoutée le:</dt>
              <dd class="col-11">
                {{ image.uploaded_on }}
              </dd>

              <dt class="col-1">Par:</dt>
              <dd class="col-11">
                {{ image.id_artist }}
              </dd>

              <dt class="col-1">Source:</dt>
              <dd class="col-11">
                <a :href="image.source" target="_blank">{{ image.source }}</a>
              </dd>

              <dt class="col-1">URL d'origine:</dt>
              <dd class="col-11">
                <a :href="image.url" target="_blank">{{ image.url }}</a>
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>

    <Dialog header="Tags" v-model:visible="this.displayModal" :modal="false" class="w-50">

      <DataTable v-model:selection="this.image.tags"
                 v-model:filters="this.filters"
                 :value="this.getTags"
                 :paginator="true"
                 :rows="10"
                 :rowHover="true"
                 sortField="oui"
                 sortOrder="1"
                 dataKey="id"
                 filterDisplay="menu"
                 responsiveLayout="scroll">
        <template #header>
          <div class="p-d-flex p-jc-end">
            <span class="p-input-icon-left ">
              <i class="pi pi-search" />
              <InputText v-model="this.filters['global'].value" placeholder="Keyword Search" />
            </span>
          </div>
        </template>
        <Column selectionMode="multiple"></Column>
        <Column field="name" header="Nom" :sortable="true"></Column>
        <Column field="type.name" header="Type" :sortable="true"></Column>
        <Column field="description" header="Description" :sortable="true"></Column>
      </DataTable>

      <template #footer>
        <Button label="Annuler" icon="pi pi-times" class="p-button-text" @click="closeModal"/>
        <Button label="Enregistrer" icon="fas fa-save" @click="saveTags"/>
      </template>
    </Dialog>


    <div class="text-start mb-3">
      <img v-if="source === 'local'" :src="getLocalSource" :alt="image.filename" class="img-fluid"/>
      <img v-if="source === 'pixiv'" :src="oSource" :alt="image.filename" class="img-fluid"/>
    </div>


  </div>
</template>

<script lang="js">
import {defineComponent} from 'vue';
import axios from "axios";
import {useToast} from "vue-toastification";
import {useI18n} from "vue-i18n";
import {useStore} from "./../../store/tags";
import {useImageStore} from "./../../store/image";
import {mapState, mapWritableState} from 'pinia';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import {FilterMatchMode, FilterOperator} from 'primevue/api';
import Dialog from "primevue/dialog";
import InputText from 'primevue/inputtext';

export default defineComponent({
    name: 'ImageView',
    components: {
        Button,
        Checkbox,
        Column,
        DataTable,
        Dialog,
        InputText
    },
    computed: {
        ...mapState(useStore, ['getTags']),
        ...mapWritableState(useImageStore, ['image']),
        getLocalSource() {
            if (this.image.id) {
                return process.env.VITE_API_URL + '/image/file/' + this.image.id
            }
            return '';
        },
        tags() {
            const store = useStore();
            return [store.getTags, []];
        }
    },
    data() {
        return {
            // image: useImageStore().image,
            oSource: '',
            source: 'local',
            displayModal: null,
            filters: {
                'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
                'name': {value: null, matchMode: FilterMatchMode.STARTS_WITH},
                'type': {value: null, matchMode: FilterMatchMode.STARTS_WITH},
                'description': {value: null, matchMode: FilterMatchMode.IN}
            },
            selectedTags: null
        }
    },
    methods: {
        openModal() {
            this.displayModal = true;
        },
        closeModal() {
            this.displayModal = false;
        },
        saveTags() {
            axios.post('/image/update', this.image).then((response, error) => {
                if(response.status === 200){
                    this.toast.success('Tags mis à jour !');
                    this.closeModal();
                }
            });
        },
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
        switchSource() {
            this.source = (this.source === 'local' ? 'pixiv' : 'local');
        }
    },
    async mounted() {
        const tagStore = useStore();
        const imageStore = useImageStore();
        await axios.get('/image/get/' + this.$route.params.id, {
            headers: {
                'Wait': true
            }
        }).then((response, error) => {
            imageStore.image = response.data;
        });

        axios.post('/pixiv/original', {image: this.image}).then((response) => {
            const data = response.data;
            const type = data.data;
            const base64 = data.base64;
            this.oSource = 'data:' + type + ';base64,' + base64;
            return true;
        });
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

::v-deep(.p-chips) {
    display: block;
}

::v-deep(.p-picklist-source-controls) {
    display: none;
}

::v-deep(.p-picklist-target-controls) {
    display: none;
}

::v-deep(.p-picklist-item) {
    padding: 0.25rem !important;
}

</style>
