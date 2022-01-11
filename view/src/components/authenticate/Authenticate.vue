<template>
  <div class="container d-flex flex-column justify-content-center align-items-center">
    <router-view/>
  </div>
</template>

<script lang="js">
import {defineComponent} from 'vue';

export default defineComponent({
    name: 'Authenticate',
    components: {},
    props: {},
});
</script>
