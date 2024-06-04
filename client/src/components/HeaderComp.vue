<script setup lang="ts">

</script>

<template>
    <header>
        <div class="header--logo">
            <router-link to="/"><p><span class="black-text">✈️ Nex</span><span class="blue-text">Trip</span></p></router-link>
        </div>
 
        <nav class="header--nav">
            <ul>
                <li><router-link to="/">Home</router-link></li>
                <li><router-link to="/about">About</router-link></li>
                <li><button><router-link to="/chooseNextTrip" class="header--btn">Trip!</router-link></button></li>
            </ul>
        </nav>
    </header>
</template>

<style lang="scss" scoped>
header {
    width: 100%;
    height: 20px;
    padding: 2rem;

    background: rgba(255, 255, 255, 0.05);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
    backdrop-filter: blur(3.9px);
    -webkit-backdrop-filter: blur(3.9px);
    border: 1px solid rgba(255, 255, 255, 0.45);

    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;

    a {
        color: black;
        text-decoration: none;
    }

    .header--logo {
        background-size: contain    ;
    }

    .header--nav {
        ul {
            list-style: none;
            display: flex;
            gap: 1rem;
        }
    }

    .header--btn {
        background-color: #101010;
        color: #e1e1e1;
        padding: 4px 8px 4px 8px;
        border-radius: 12px;
    }

    a.active,
    a.exact-active {
        color: #3e96bf;
        font-weight: 500;
    }

    li button {
        border: none;

        a.active,
        a.exact-active {
            color: white;
            background-color: #006fb9;
            font-weight: 400;
        }
    }
}
</style>