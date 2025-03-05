<script setup>
import { ref, onMounted , computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link } from '@inertiajs/vue3';


const exams = ref([]);

onMounted(() => {
    exams.value = usePage().props.exams;
    console.log(exams.value);
});

// تصفية الاختبارات التي تحتوي على نتائج فقط
const completedExams = computed(() => {
    return exams.value.filter(exam => exam.results !== null);
});

// حساب المجموع الكلي للدرجات للأسئلة المكتملة
const totalScore = computed(() => {
    return completedExams.value.reduce((sum, exam) => sum + exam.results.score, 0);
});

// حساب المجموع الكلي للأسئلة للأسئلة المكتملة
const totalQuestions = computed(() => {
    return completedExams.value.reduce((sum, exam) => sum + exam.questions_count, 0);
});
</script>

<template>

    <Head title="Exam List" />
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Exam list</h1>
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">#</th>
                    <th class="border p-2">Title</th>
                    <th class="border p-2">Duration</th>
                    <th class="border p-2">Number of questions</th>
                    <th class="border p-2">Score</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(exam, index) in exams" :key="exam.id">
                    <td class="border p-2">{{ index + 1 }}</td>
                    <td class="border p-2">{{ exam.title }}</td>
                    <td class="border p-2">{{ exam.duration }} minutes</td>
                    <td class="border p-2">{{ exam.questions_count }}</td>
                    <td v-if="exam.results !== null" class="border p-2 text-green-600 font-bold">
                        {{ exam.results.score }} out of {{ exam.questions_count }}
                    </td>
                    <td v-else class="border p-2 text-blue-500">
                        <Link :href="route('exams.show', exam.id)">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                            Start Exam
                        </button>
                        </Link>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="border p-2 font-bold text-right">Total Score</td>
                    <td class="border p-2 text-green-600 font-bold">
                        {{ totalScore }} out of {{ totalQuestions }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>
