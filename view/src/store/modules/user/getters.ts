import { State } from './state';

export const ANONYMOUS_LOGIN = 'anonymous';

export type Getters = {
  getLogin(state: State): string | null;
  getFirstName(state: State): string | null;
  getLastName(state: State): string | null;
  isLogged(state: State): boolean;
}

export const getters: Getters = {
  getLogin(state: State) {
    if (!state.isLogged || state.login === null) return ANONYMOUS_LOGIN;
    return state.login;
  },

  getFirstName(state: State) {
    if (!state.isLogged) return null;
    return state.firstName;
  },

  getLastName(state: State) {
    if (!state.isLogged) return null;
    return state.lastName;
  },

  isLogged(state: State) {
    return state.isLogged;
  },
};
