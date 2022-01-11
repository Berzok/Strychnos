<template>
  <Header v-if="!['login', 'authenticate', 'Home'].includes(this.$route.name)"/>
  <Spinner/>
  <div class="d-flex flex-nowrap">
    <Navbar v-if="!['login', 'authenticate', 'Home'].includes(this.$route.name)"/>
    <router-view class="mx-3 py-4"/>
  </div>
</template>

<script lang="js">
import {defineComponent} from 'vue';
import {mapWritableState} from 'pinia';
import {useStore} from "./store/tags";
import Header from './components/Header.vue';
import Spinner from './components/Spinner.vue';
import Navbar from './components/Navbar.vue';

export default defineComponent({
    name: 'App',
    components: {
        Header,
        Spinner,
        Navbar
    },
});
</script>

<style lang="scss">
//needed to import bootsteap icons (otherwhise <i class="bi-***"> will not be displayed)
@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css");


#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}

html {
  scrollbar-width: thin;
  scrollbar-color: #7d22a8 #001773;
}

#nav {
  padding: 30px;

  a {
    font-weight: bold;
    color: #2c3e50;

    &.router-link-exact-active {
      color: #42b983;
    }
  }
}
</style>
