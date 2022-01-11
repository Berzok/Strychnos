import Axios from 'axios';
import {useToast} from 'vue-toastification';
import router from './../router/index';
import {loadStore} from '../store/modules/loading';
import {ActionTypes as loadingActionTypes} from './../store/modules/loading/actions';

function configure(configuration) {
    // TODO add an option to disable by default loading
    if(configuration.headers.Wait) {
        loadStore().dispatch(loadingActionTypes.beginLoading, undefined);
    }
    const conf = configuration;
    conf.timeout = 180000;
    conf.baseURL = process.env.VITE_API_URL;
    return conf;
}

function requestErrorHandler(error) {
    loadStore().dispatch(loadingActionTypes.endLoading, undefined);
    useToast().error('Invalid request');
    return Promise.reject(error);
}

function responseHandler(response) {
    loadStore().dispatch(loadingActionTypes.endLoading, undefined);
    if (response.status !== 200 && response.status !== 201 && response.status !== 204) {
        useToast().error(`Request failed ${response.status} : ${response.statusText}`);
    }
    return Promise.resolve(response);
}

function responseErrorHandler(error) {
    let disableNotif = false;
    loadStore().dispatch(loadingActionTypes.endLoading, undefined);
    if (error.response) {
        switch (error.response.status) {
            case 408:
                console.log(`A timeout happend on url ${error.config.url}`);
                break;
            case 401:
                console.log('401');
                /* store.dispatch('logout');
                store.dispatch('resetCurrentUserProfile'); */
                router.replace('/authenticate/login');
                disableNotif = true;
                break;
            default:
                // do something
                console.log('interceptor');
                console.dir(error.response);
                break;
        }
        // let message = i18n.global.t('authenticate.sucessMessage');
        let message = `Request failed ${error.response.status} : ${error.response.statusText} `;
        if (error.response.data !== undefined
            && error.response.data.error !== undefined
            && error.response.data.error !== '') {
            message = error.response.data.error;
        }
        if (!disableNotif) {
            useToast().error(message);
        } else {
            console.log(message);
        }
    }
    return Promise.reject(error);
}

export default function configureHTTPInterceptor() {
    Axios.interceptors.request.use((config) => configure(config), (error) => requestErrorHandler(error));
    Axios.interceptors.response.use((response) => responseHandler(response), (error) => responseErrorHandler(error));
}