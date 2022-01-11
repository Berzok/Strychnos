<template>
  <div class="card w-50">

    <div class="card-header">
      <img src="../../assets/box.jpg" class="card-img-top" alt="Box">
    </div>

    <form @submit.prevent="">
      <div class="card-body mx-auto w-75">


        <div class="row input-group">
          <label id="loginLabel" class="col-4 col-form-label input-group-text">
            {{ $t('login.login') }}
          </label>
          <input v-model="login" id="login" class="form-control"/>
        </div>

        <div class="row input-group">
          <label id="passwordLabel" class="col-4 col-form-label input-group-text">
            {{ $t('login.password') }}
          </label>
          <input v-model="password" id="password" type="password" class="form-control"/>
        </div>

      </div>

      <div class="card-footer">
        <button class="btn btn-outline-info w-100" @click="log">{{ $t('authenticate.login') }}</button>
      </div>
    </form>

  </div>
</template>

<script lang="js">
import {useToast} from 'vue-toastification';
import {defineComponent} from 'vue';
import {useI18n} from 'vue-i18n';
import {useStore as useAuthenticationStore} from './../../store/modules/authentication';
import {ActionTypes as AuthenticationActionTypes} from './../../store/modules/authentication/actions';
import {useStore as userUSerStore} from './../../store/modules/user';
import {ActionTypes as UserActionTypes} from './../../store/modules/user/actions';
import router, {HOME_PAGE_NAME} from './../../router';

export default defineComponent({
    name: 'Login',
    components: {},
    setup() {
        // Get toast interface
        const toast = useToast();
        const {t, locale} = useI18n();
        return {toast, t, locale};
    },
    data() {
        return {
            login: '',
            password: '',
        };
    },
    methods: {
        async log() {
            const isLogged = await useAuthenticationStore()
                .dispatch(AuthenticationActionTypes.login, {login: this.login, password: this.password});
            if (!isLogged) {
                this.toast.error(this.t('authenticate.failMessage'));
                return;
            }
            this.toast.success(`${this.t('authenticate.sucessMessage')} ${this.login}`);
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
</style>
