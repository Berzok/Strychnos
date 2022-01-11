<template>
  <div class="card ml-auto">
    <div class="row">
      <div class="input-group d-flex justify-content-center">
        <div class="input-group-prepend">
          <label class="input-group-text font-weight-bold" id="loginLabel">
            {{ $t('register.login') }}
          </label>
        </div>
        <input v-model="login" id="login"/>
      </div>
    </div>
    <div class="row">
      <div class="input-group d-flex justify-content-center">
        <div class="input-group-prepend">
          <label class="input-group-text font-weight-bold" id="firstNameLabel">
            {{ $t('register.firstName') }}
          </label>
        </div>
        <input v-model="firstName" id="firstName"/>
      </div>
    </div>
    <div class="row">
      <div class="input-group d-flex justify-content-center">
        <div class="input-group-prepend">
          <label class="input-group-text font-weight-bold" id="lastNameLabel">
            {{ $t('register.lastName') }}
          </label>
        </div>
        <input v-model="lastName" id="lastName"/>
      </div>
    </div>
    <div class="row">
      <div class="input-group d-flex justify-content-center">
        <div class="input-group-prepend">
          <label class="input-group-text font-weight-bold" id="passwordLabel">
            {{ $t('register.password') }}
          </label>
        </div>
        <input v-model="password" id="password" type="password"/>
      </div>
    </div>
    <div class="row">
      <button variant="success" @click="register">
        {{ $t('authenticate.register') }}
      </button>
    </div>
  </div>
</template>

<script lang="js">
import {defineComponent} from 'vue';
import {useStore as useAuthenticationStore} from './../../store/modules/authentication';
import {ActionTypes as AuthentcationActionTypes} from './../../store/modules/authentication/actions';
import {useStore as userUSerStore} from './../../store/modules/user';
import {ActionTypes as UserActionTypes} from './../../store/modules/user/actions';
import router, {HOME_PAGE_NAME} from './../../router';

export default defineComponent({
    name: 'Register',
    components: {},
    data() {
        return {
            login: '',
            password: '',
            firstName: '',
            lastName: '',
        };
    },
    methods: {
        async register() {
            await useAuthenticationStore()
                .dispatch(AuthentcationActionTypes.register, {
                    login: this.login,
                    password: this.password,
                    firstName: this.firstName,
                    lastName: this.lastName,
                });
            userUSerStore().dispatch(UserActionTypes.login, this.login);
            router.push({name: HOME_PAGE_NAME});
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
