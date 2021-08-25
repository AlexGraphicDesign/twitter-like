<template>
    <app-layout title="Profile">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Profil de {{ profileUser.name}}
                </h2>
                <div v-if="profileUser.id != $page.props.user.id">
                    <inertia-link v-if="!profileUser.is_followed" as="button" :href="`/follows/${profileUser.id}`" method="POST" class="bg-white text-blue-500 cursor-pointer px-5 py-2 hover:text-white border border-blue-500 leading-tight hover:bg-blue-500 rounded-full font-extrabold transition-all duration-300" preserve-scroll>Suivre</inertia-link>
                    <inertia-link v-else as="button" :href="`/unfollows/${profileUser.id}`" method="POST" class="bg-white text-blue-500 cursor-pointer px-5 py-2 hover:text-white border border-blue-500 leading-tight hover:bg-blue-500 rounded-full font-extrabold transition-all duration-300" preserve-scroll>Ne plus suivre</inertia-link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div v-for="tweet in tweets" v-bind:key="tweet.id" class="flex items-center space-x-4 bg-white overflow-hidden rounded rounded-b-none border-b-2 border-gray-200 py-6 px-4">
                    <img class="h-16 w-16 object-cover rounded-full" :src="profileUser.profile_photo_url">
                    <div class="flex flex-col w-2/3">
                    <div>
                        <a class="text-sm text-gray-900 font-bold hover:text-blue-400" :href="`/profile/${profileUser.name}`">
                            {{ profileUser.name }}
                        </a>
                        <span class="font-thin text-gray-400">· le {{ tweet.created_at }}</span>
                        <p class="text-sm text-blue-500" v-if="profileUser.is_following_you">Vous suit</p>
                    </div>
                    <div class="text-sm text-gray-400 font-thin">{{ tweet.content }}</div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout.vue'
    import TweetCreate from '@/Pages/Tweets/Create.vue'

    export default {

        components: {
            AppLayout
        },

        props: {
            profileUser: Object,
            tweets: Array,
            user: Object
        }

    }
</script>
