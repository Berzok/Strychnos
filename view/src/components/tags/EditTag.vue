<template>

  <div class="d-flex flex-column">
    <div class="d-flex justify-content-between mb-3">
      <div class="flex-fill">
        <label for="name">Name: </label>
        <InputText id="name" v-model="tag.name" required="true" autofocus/>
      </div>

      <div class="mx-4"></div>

      <div class="flex-fill">
        <label for="type">Type: </label>
        <select v-model="tag.type" class="form-control form-select">
          <option v-for="t in this.types" :key="t" :value="t">
            {{ t.name }}
          </option>
        </select>
      </div>
    </div>

    <div class="">
      <label for="description">Description</label>
      <Textarea id="description" v-model="tag.description" required="true" rows="3" cols="20"/>
    </div>

    <div class="d-flex justify-content-around" @click="hideDialog">
      <button class="btn btn-warning">
        <span class="fas fa-times"></span>
        Cancel
      </button>
      <button class="btn btn-success" @click="saveTag">
        <span class="fas fa-check"></span>
        Save
      </button>
    </div>
  </div>

</template>

<script lang="js">
import {useToast} from 'vue-toastification';
import {defineComponent} from 'vue';
import axios from 'axios';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';

export default defineComponent({
    name: 'EditTag',
    components: {
        InputNumber,
        InputText,
        Textarea
    },
    props: {
        types: Array,
        modelValue: null
    },
    data() {
        return {
            toast: useToast(),
            tag: {}
        };
    },
    mounted() {
        if(this.modelValue){
            this.tag = this.modelValue;
        }
    },
    methods: {
        saveTag() {
            axios.put('/tag/save', this.tag).then((response, error) => {
                this.toast.success('ok');
            });
        },
        hideDialog() {

        }
    },
});
</script>

<style scoped>

</style>