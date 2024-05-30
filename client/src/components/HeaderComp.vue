<script setup lang="ts">

</script>

<template>
    <header>
        <div class="header--logo">
            <a href="/"><p>LOGO</p></a>
        </div>

        <nav class="header--nav">
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
        </nav>
    </header>
</template>

<style lang="scss" scoped>
header {
    width: 100%;
    height: 20px;
    padding: 2rem 6rem 2rem 6rem;

    background: rgba(255, 255, 255, 0.05);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(3.9px);
    -webkit-backdrop-filter: blur(3.9px);
    border: 1px solid rgba(255, 255, 255, 0.45);

    display: flex;
    justify-content: space-between;
    align-items: center;

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
            gap: 2rem;
        }
    }
}
</style>