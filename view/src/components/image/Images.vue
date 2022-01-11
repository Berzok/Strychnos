<template>
  <div class="flex-fill">

    <router-view/>

  </div>
</template>

<script lang="js">
import {useToast} from 'vue-toastification';
import {defineComponent} from 'vue';
import axios from 'axios';
import {useI18n} from 'vue-i18n';
import {useStore as useAuthenticationStore} from './../../store/modules/authentication';
import {
    ActionTypes as AuthenticationActionTypes,
    USER_API_ENDPOINT
} from './../../store/modules/authentication/actions';
import DataView from 'primevue/dataview';
import {useStore as userUSerStore} from './../../store/modules/user';
import {ActionTypes as UserActionTypes} from './../../store/modules/user/actions';
import router, {HOME_PAGE_NAME} from './../../router';
import ImageThumbnail from "./ImageThumbnail";

export default defineComponent({
    name: 'Images',
    setup() {
        // Get toast interface
        const toast = useToast();
        const {t, locale} = useI18n();
        return {toast, t, locale};
    },
    data() {
        return {
            images: {}
        };
    },
    methods: {
    },
});
</script>

<style scoped>

img {
    width: 220px;
}
</style>
