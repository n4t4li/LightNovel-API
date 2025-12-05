<template>
    <div>
        <h1>Light Novels</h1>

        <input
            type="text"
            v-model="search"
            placeholder="Search..."
            @input="filterNovels"
        />

        <table v-if="filteredNovels.length">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Statut</th>
                    <th>Chapitres</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="novel in filteredNovels" :key="novel.id">
                    <td>{{ novel.id }}</td>
                    <td>{{ novel.titre }}</td>
                    <td>{{ novel.auteur }}</td>
                    <td>{{ novel.statut }}</td>
                    <td>{{ novel.chapitres }}</td>
                    <td>
                        <router-link
                            :to="{
                                name: 'lightnovels_show',
                                params: { id: novel.id },
                            }"
                            >View</router-link
                        >
                        |
                        <router-link
                            :to="{
                                name: 'lightnovels_edit',
                                params: { id: novel.id },
                            }"
                            >Edit</router-link
                        >
                    </td>
                </tr>
            </tbody>
        </table>

        <p v-else>No light novels found.</p>

        <router-link :to="{ name: 'lightnovels_create' }"
            >Add New Light Novel</router-link
        >
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "IndexLightNovels",
    data() {
        return {
            lightNovels: [],
            filteredNovels: [],
            search: "",
        };
    },
    methods: {
        async fetchNovels() {
            try {
                const res = await axios.get("/api/lightnovels");
                this.lightNovels = res.data.data;
                this.filteredNovels = this.lightNovels;
            } catch (err) {
                console.error(err);
            }
        },
        filterNovels() {
            const s = this.search.toLowerCase();
            this.filteredNovels = this.lightNovels.filter(
                (novel) =>
                    novel.titre.toLowerCase().includes(s) ||
                    novel.auteur.toLowerCase().includes(s)
            );
        },
    },
    mounted() {
        this.fetchNovels();
    },
};
</script>
