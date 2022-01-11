import axios from 'axios';
import {useStore as userUSerStore} from "../user";
import {ActionTypes as UserActionTypes} from "../user/actions";

//export const USER_API_ENDPOINT = `${process.env.VUE_APP_API_BASE_URL}/login`;
export const USER_API_ENDPOINT = `/login`;
export var ActionTypes;

(function (ActionTypes) {
    ActionTypes["login"] = "LOGIN";
    ActionTypes["register"] = "REGISTER";
})(ActionTypes || (ActionTypes = {}));

export const actions = {

    /**
     * LOGIN action
     * @param state
     * @param {object} authentication - Object containing the form fields
     * @returns {Promise<AxiosResponse<any> | boolean>}
     */
    async [ActionTypes.login]({state}, authentication) {

        //POST request to the server
        //return axios.post(USER_API_ENDPOINT, authentication);

        return axios.post(USER_API_ENDPOINT, authentication)
            .then(function(response){

                //What is the response status ?
                const data = response.data;
                console.dir(data);
                if(data.status === 1){
                    const user = data.userData;
                    userUSerStore().dispatch(UserActionTypes.setLastName, user.username);
                    return true;
                } else{
                    return false;
                }

            }).catch(() => false);

    },

    async [ActionTypes.register]({dispatch}, user) {
        await axios.post(USER_API_ENDPOINT, user);
        return dispatch(ActionTypes.login, {
            login: user.login,
            password: user.password,
        });
    },
};
//# sourceMappingURL=actions.js.map