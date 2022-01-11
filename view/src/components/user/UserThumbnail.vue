<template>
  <div class="card">
    <em class="bi-person-circle fs-2 card-img-top"></em>
    <h5 class="card-title" id="login">{{ firstName + ' ' + lastName }}</h5>
  </div>
</template>

<script lang="js">
import {computed, defineComponent} from 'vue';
import {useStore as useUserStore} from './../../store/modules/user';

export default defineComponent({
    name: 'UserThumbnail',
    setup() {
        const store = useUserStore();
        const login = computed(() => store.getters.getLogin);
        const firstName = computed(() => store.getters.getFirstName);
        const lastName = computed(() => store.getters.getLastName);
        return {login, firstName, lastName};
    },
});
</script>
