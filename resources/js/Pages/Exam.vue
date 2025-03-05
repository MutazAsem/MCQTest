<script setup>
import { ref, defineProps , onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps(['exam', 'questions']);
const answers = ref({});


const form = useForm({
    exam_id: props.exam.id,
    answers: {} // يجب أن تكون answers كائن فارغ عند التهيئة
});



const submitExam = () => {
    console.log("الإجابات المرسلة:", answers.value); // تحقق من البيانات قبل الإرسال
    form.post(route('saveResult'), {
        onSuccess: () => {
            alert("تم إرسال الاختبار بنجاح!");
        }
    });
};

const remainingTimeInSeconds = ref(props.exam.duration * 60); // تحويل الدقائق إلى ثواني
const timer = ref(null);

const formatTime = (timeInSeconds) => {
    const minutes = Math.floor(timeInSeconds / 60);
    const seconds = timeInSeconds % 60;
    return `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`; // تنسيق الوقت كـ "دقائق:ثواني"
};

const startCountdown = () => {
    timer.value = setInterval(() => {
        if (remainingTimeInSeconds.value > 0) {
            remainingTimeInSeconds.value -= 1; // تقليص الوقت ثانية ثانية
        } else {
            clearInterval(timer.value); // إيقاف العداد عند نفاد الوقت
            submitExam(); // إرسال الإجابات بعد انتهاء الوقت
        }
    }, 1000); // تحديث كل ثانية
};
onMounted(() => {
    startCountdown(); // بدء العد التنازلي عند تحميل الصفحة
});
</script>

<template>

    <Head title="Exam" />
    <div class="container mx-auto p-6">
        <form @submit.prevent="submitExam">
            <div class="flex flex-col items-center">
                <h1 class="text-xl font-bold">{{ exam.title }}</h1>
                <p>{{ exam.description }}</p>
                <div class="mt-2 text-lg">
                    <span>الوقت المتبقي: </span>
                    <span>{{ formatTime(remainingTimeInSeconds) }}</span> <!-- عرض الوقت بالدقائق والثواني -->
                </div>
            </div>
            <div v-for="(question, index) in questions" :key="question.id">
                <div class="flex flex-row justify-between items-center p-2">
                    <ul class="mt-4">
                        <li class="mb-4">
                            <p class="text-lg font-bold">Q{{ index + 1 }} {{ question.question }} </p>
                            <ul class="my-2">
                                <li v-for="answer in question.answers" :key="answer.id">
                                    <input type="radio" v-model="form.answers[question.id]" :name="question.id"
                                        :value="answer.id" />
                                    {{ answer.answer_text }}
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div v-if="question.type !== 'Text'">
                        <template v-if="question.type === 'Image'">
                            <img :src="`/storage/${question.media_path}`" class="w-64 h-64" alt="Question Image" />
                        </template>
                        <template v-else-if="question.type === 'Audio'">
                            <audio controls>
                                <source :src="`/storage/${question.media_path}`" type="audio/mpeg" />
                                Your browser does not support the audio element.
                            </audio>
                        </template>
                        <template v-else-if="question.type === 'Video'">
                            <video controls class="w-64 h-64">
                                <source :src="`/storage/${question.media_path}`" type="video/mp4" />
                                Your browser does not support the video tag.
                            </video>
                        </template>
                    </div>
                </div>
                <hr class="border-2 border-black" />
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                    End the Exam
                </button>
            </div>
        </form>
    </div>
</template>
