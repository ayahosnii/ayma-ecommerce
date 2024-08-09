<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import FlashCard from 'vue-flashcard';
import { BASE_URL } from '@/config/apiConfig';

// Define types
interface FlashCardData {
  id: number;
  data: string;
  audio?: string;
  image?: string;
  text?: string;
}

interface AnswerResponse {
  message: string;
  correct: boolean;
  correct_answers: number;
  incorrect_answers: number;
  next_flash_card: {
    flash_card_id: number;
    data: string;
  };
}

// State variables
const flashCardData = ref<FlashCardData | null>(null);
const answer = ref('');
const flashCardId = ref(0);
const message = ref('');
const correctAnswers = ref(0);
const incorrectAnswers = ref(0);

// Function to get token from localStorage
const getToken = (): string | null => {
  if (typeof window !== 'undefined' && window.localStorage) {
    return localStorage.getItem('authToken');
  }
  return null;
};

// Fetch a random flash card
const fetchRandomFlashCard = async () => {
  const token = getToken();
  if (!token) {
    console.error('Token not found');
    return;
  }

  try {
    const response = await axios.get(`${BASE_URL}/flashcards/random`, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    flashCardData.value = response.data.data;
    flashCardId.value = response.data.flash_card_id;
  } catch (error) {
    console.error('Error fetching flash card:', error);
  }
};

// Submit the answer
const submitAnswer = async () => {
  const token = getToken();
  if (!token) {
    console.error('Token not found');
    return;
  }

  if (!flashCardData.value) return;

  try {
    const response = await axios.post<AnswerResponse>(
      `${BASE_URL}/flashcards/answer/${flashCardId.value}`,
      {
        answer: answer.value,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }
    );

    console.log(response.data.next_flash_card.data)
    message.value = response.data.message;
    correctAnswers.value = response.data.correct_answers;
    incorrectAnswers.value = response.data.incorrect_answers;
    flashCardData.value = response.data.next_flash_card.data;
    answer.value = '';
  } catch (error) {
    console.error('Error submitting answer:', error);
  }
};

// Fetch the initial flash card on component mount
onMounted(fetchRandomFlashCard);
</script>

<template>
  <div>
    <h1>Flash Card Game</h1>

    <div v-if="flashCardData">
      {{flashCardData.image}}
      <div v-if="flashCardData.image" v-html="flashCardData.image"></div>
      <div v-if="flashCardData.audio" v-html="flashCardData.audio"></div>
      <div v-if="flashCardData.text" v-html="flashCardData.text"></div>
      <div v-else>
        <div v-html="flashCardData.data"></div>
      </div>
      <div>
        <input v-model="answer" placeholder="Enter your answer" />
        <button @click="submitAnswer">Submit</button>
      </div>
    </div>

    <div v-else>
      <p>Loading...</p>
    </div>

    <div v-if="message">
      <p>{{ message }}</p>
    </div>

    <div>
      <p>Correct Answers: {{ correctAnswers }}</p>
      <p>Incorrect Answers: {{ incorrectAnswers }}</p>
    </div>
  </div>
</template>

<style scoped>
/* Add your styles here */
</style>
