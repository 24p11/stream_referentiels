<template>
    <div style="display: contents">
        <tr>
            <th scope="row">{{referential.id}}</th>
            <td>{{referential.ref_id}}</td>
            <td>{{referential.label}}</td>
            <td>{{referential.start_date}}</td>
            <td>{{referential.end_date}}</td>
            <td @click="display"><p :class="hasMetadata">Afficher</p></td>
        </tr>
        <tr v-show="show">
            <td class="no-padding" colspan="6">
                <pre v-highlightjs><code class="json">{{referential.metadata}}</code></pre>
            </td>
        </tr>
    </div>
</template>

<script>
    export default {
        name: 'ReferentialItem',
        props: ['referential'],
        data: () => {
            return {
                show: false
            }
        },
        methods: {
            display() {
                if (this.referential.metadata.length) {
                    this.show = !this.show
                }
            }
        },
        computed: {
            hasMetadata: function () {
                return this.referential.metadata.length
                    ? 'text-primary'
                    : 'text-secondary'
            }
        }
    }
</script>

<style>
    td.no-padding {
        padding: 0;
    }

    p.text-primary {
        cursor: pointer;
    }

    p.text-secondary {
        text-decoration: line-through;
    }

    .json {
        background-color: #fdfdfd;
    }
</style>
