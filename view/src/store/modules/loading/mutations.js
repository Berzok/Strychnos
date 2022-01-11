export var MutationType;
(function (MutationType) {
    MutationType["beginLoading"] = "BEGIN_LOADING";
    MutationType["endLoading"] = "END_LOADING";
})(MutationType || (MutationType = {}));
export const mutations = {
    [MutationType.beginLoading](state) {
        state.pendingLoading += 1;
    },
    [MutationType.endLoading](state) {
        state.pendingLoading -= 1;
        // TODO : fire an error instead
        if (state.pendingLoading < 0) {
            state.pendingLoading = 0;
        }
    },
};
//# sourceMappingURL=mutations.js.map