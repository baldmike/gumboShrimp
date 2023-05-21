<template>
    <b-container fluid>
      <div class="header-banner">
        <h1 class="banner">Chango Cart</h1>
        <div class="subtitle">A place to buy stuff</div>
      </div>
    </b-container>
  </template>
  
  <script>
  import { mapGetters } from "vuex";
  
  export default {
    name: "headerComponent",
  
    computed: mapGetters(["isAuthenticated"]),
  
    mounted() {
      console.log("Header Component mounted.");
    },
  };
  </script>
  
  <style>
  * {
    font-family: "Staatliches", cursive;
  }
  
  .header-banner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
  }
  
  .banner {
    font-size: 5rem;
    color: #ff5500;
    margin-bottom: 0;
    font-family: "Staatliches", cursive;
  }
  
  .subtitle {
    font-size: 2rem;
    color: #666666;
  }
  </style>
  