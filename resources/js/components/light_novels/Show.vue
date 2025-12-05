<template>
    <div v-if="novel">
        <h1>{{ novel.titre }}</h1>
        <p>Author: {{ novel.auteur }}</p>
        <p>Status: {{ novel.statut }}</p>
        <p>Chapters: {{ novel.chapitres }}</p>
        <p>{{ novel.Contenu }}</p>
        <img v-if="novel.photo" :src="imageUrl(novel.photo)" width="200" />

        <router-link :to="`/lightnovels/${novel.id}/edit`">Edit</router-link>
        <router-link to="/lightnovels">Back</router-link>

        <h2>Commentaires</h2>
        <ul v-if="novel.commentaires.length">
            <li v-for="c in novel.commentaires" :key="c.id">
                <strong>{{ c.user?.name ?? "Anonymous" }}:</strong>
                {{ c.texte }}
            </li>
        </ul>
        <p v-else>No comments yet.</p>
        <div v-if="isLoggedIn">
            <h3>Ajouter un commentaire</h3>
            <form @submit.prevent="addComment">
                <textarea
                    v-model="newComment"
                    placeholder="Écrit votre commentaire..."
                    required
                ></textarea>
                <br />
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</template>

<script>
import api from "../../axios";

export default {
    data() {
        return {
            novel: null,
            newComment: "",
            isLoggedIn: true,
        };
    },
    methods: {
        async fetchNovel() {
            const { id } = this.$route.params;
            const res = await api.get(`/api/lightnovels/${id}`);
            this.novel = res.data.data;
        },
        imageUrl(photo) {
            if (!photo) return '';
            if (photo.startsWith('http://') || photo.startsWith('https://') || photo.startsWith('/')) {
                return photo;
            }
            return `/images/${photo}`;
        },
        async addComment() {
            if (!this.newComment.trim()) return;

            await api.post(`/api/lightnovels/${this.novel.id}/commentaires`, {
                texte: this.newComment,
            });

            this.newComment = "";
            await this.fetchNovel();
        },
    },
    async mounted() {
        await this.fetchNovel();
    },
};
</script>
