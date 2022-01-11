import { State } from './state';

export type Getters = {
  isLoading(state: State): boolean;
}

export const getters: Getters = {
  isLoading(state: State) {
    return state.pendingLoading > 0;
  },
};
