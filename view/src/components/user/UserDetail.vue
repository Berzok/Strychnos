<template>
  <div class="container mx-auto">

    <div class="row">
      <h1 class="my-4 fw-bold text-info">Informations</h1>
    </div>

    <div class="mx-auto w-50">
      <div class="row input-group">
        <label class="col-4 col-form-label input-group-text" id="loginLabel">login</label>
        <input v-model="login" id="login" class="form-control"/>
      </div>
      <div class="row input-group">
        <label class="col-4 col-form-label input-group-text font-weight-bold" id="firstNameLabel">first name</label>
        <input v-model="firstName" id="firstName" class="form-control"/>
      </div>
      <div class="row input-group">
        <label class="col-4 col-form-label input-group-text font-weight-bold" id="lastNameLabal">last name</label>
        <input v-model="lastName" id="lastName" class="form-control"/>
      </div>
    </div>
  </div>
</template>

<script lang="js">
import {defineComponent} from 'vue';
import {useStore as useUserStore} from './../../store/modules/user';
import {ActionTypes} from './../../store/modules/user/actions';

export default defineComponent({
    name: 'UserDetail',
    computed: {
        login: {
            get() {
                return useUserStore().getters.getLogin;
            },
            /**
             * @param {string} newLogin
             */
            set(newLogin) {
                useUserStore().dispatch(ActionTypes.setLogin, newLogin);
            },
        },
        firstName: {
            get() {
                return useUserStore().getters.getFirstName;
            },
            set(newFirstNAme) {
                useUserStore().dispatch(ActionTypes.setFirstName, newFirstNAme);
            },
        },
        lastName: {
            get() {
                return useUserStore().getters.getLastName;
            },
            set(newLastName) {
                useUserStore().dispatch(ActionTypes.setLastName, newLastName);
            },
        },
    },
});
</script>

<style scoped>
.input-group {
    margin-top: 9px;
}

.input-group-prepend {
    min-width: 10%;
}

.input-group-prepend label {
    width: 100%;
    overflow: hidden;
}
</style>
