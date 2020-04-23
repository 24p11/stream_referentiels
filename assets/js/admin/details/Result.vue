<template>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Identifiant</th>
            <th scope="col">Label</th>
            <th scope="col">Date de début</th>
            <th scope="col">Date de fin</th>
            <th scope="col">Metadata</th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="performSearch">
            <td><span class="badge badge-info">Recherche en cours</span></td>
        </tr>
        <tr v-if="noResult">
            <td><span class="badge badge-warning">Pas de résultats</span></td>
        </tr>
        <template v-for="referential in repositories">
            <ReferentialItem :referential="referential"></ReferentialItem>
        </template>
        </tbody>
    </table>
</template>

<script>
    import ReferentialItem from "./ReferentialItem";

    export default {
        props: ['repositories', 'performSearch', 'noResult'],
        components: {
            ReferentialItem
        }
    }
</script>


