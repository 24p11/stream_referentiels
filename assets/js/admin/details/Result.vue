<template>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Identifiant</th>
            <th scope="col">Label</th>
            <th scope="col">Date de début</th>
            <th scope="col">Date de fin</th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="performSearch">
            <td><span class="badge badge-info">Recherche en cours</span></td>
        </tr>
        <tr v-if="noResult">
            <td><span class="badge badge-warning">Pas de résultats</span></td>
        </tr>
        <tr v-for="referential in repositories">
            <th scope="row">{{referential.id}}</th>
            <td>{{referential.ref_id}}</td>
            <td>{{referential.label}}</td>
            <td>{{referential.start_date}}</td>
            <td>{{referential.end_date}}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: ['repositories', 'performSearch', 'noResult']
    }
</script>


