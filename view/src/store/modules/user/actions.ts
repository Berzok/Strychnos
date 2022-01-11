import {ActionContext} from 'vuex';
import {Mutations, MutationType} from './mutations';
import {State} from './state';

export enum ActionTypes {
    login = 'LOGIN',
    logout = 'LOGOUT',
    setFirstName = 'SET_FIRST_NAME',
    setLastName = 'SET_LAST_NAME',
    setLogin = 'SET_LOGIN'
}

type ActionAugments = Omit<ActionContext<State, State>, 'commit'> & {
    commit<K extends keyof Mutations>(
        key: K,
        payload: Parameters<Mutations[K]>[1]
    ): ReturnType<Mutations[K]>
};

export type Actions = {
    [ActionTypes.login](context: ActionAugments, login: string): void
    [ActionTypes.logout](context: ActionAugments): void
    [ActionTypes.setFirstName](context: ActionAugments, firstName: string): void
    [ActionTypes.setLastName](context: ActionAugments, lastName: string): void
    [ActionTypes.setLogin](context: ActionAugments, login: string): void
};

export const actions: Actions = {
    [ActionTypes.login]({commit}, login: string) {
        commit(MutationType.setLogin, login);
        // in case of mutation without payload we have to pass it undefined
        commit(MutationType.login, undefined);
    },

    [ActionTypes.logout]({commit}) {
        // in case of mutation without payload we have to pass it undefined
        commit(MutationType.logout, undefined);
    },

    [ActionTypes.setFirstName]({commit}, firstName: string) {
        commit(MutationType.setFirstName, firstName);
    },

    [ActionTypes.setLastName]({commit}, lastname: string) {
        commit(MutationType.setLastName, lastname);
    },

    [ActionTypes.setLogin]({commit}, login: string) {
        commit(MutationType.setLogin, login);
    },
};
