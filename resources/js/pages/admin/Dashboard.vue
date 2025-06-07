<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import type { BreadcrumbItem } from '@/types';
import { computed } from 'vue';

// Register Chart.js components
ChartJS.register(ArcElement, Tooltip, Legend);

interface User {
  id: number;
  name: string;
  email: string;
  role: 'citizen' | 'lgu';
  created_at: string;
}

interface AssistanceData {
  labels: string[];
  counts: number[];
}

const props = defineProps<{
  totalUsers: number;
  citizenUsers: number;
  lguUsers: number;
  recentUsers: User[];
  assistanceData: AssistanceData;
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Admin Dashboard',
    href: '/admin/dashboard',
  },
];

// Compute chart data from real backend data
const chartData = computed(() => {
  const colors = ['#4A2C6A', '#F28C82', '#F7B731', '#6B2A6B', '#A63A50', '#F28767'];
  
  return {
    labels: props.assistanceData.labels,
    datasets: [{
      data: props.assistanceData.counts,
      backgroundColor: colors.slice(0, props.assistanceData.labels.length),
      borderWidth: 1,
      borderColor: '#FFFFFF'
    }]
  };
});

// Compute total assistance requests for percentage calculation
const totalAssistance = computed(() => {
  return props.assistanceData.counts.reduce((sum, count) => sum + count, 0);
});

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom' as const,
      labels: {
        color: '#333333',
        generateLabels: (chart: any) => {
          const data = chart.data;
          if (data.labels.length && data.datasets.length) {
            return data.labels.map((label: string, i: number) => {
              const count = data.datasets[0].data[i];
              const percentage = totalAssistance.value > 0 ? ((count / totalAssistance.value) * 100).toFixed(1) : '0.0';
              return {
                text: `${label} (${count} - ${percentage}%)`,
                fillStyle: data.datasets[0].backgroundColor[i],
                hidden: false,
                index: i
              };
            });
          }
          return [];
        }
      }
    },
    tooltip: {
      callbacks: {
        label: (context: any) => {
          const label = context.label || '';
          const value = context.parsed;
          const percentage = totalAssistance.value > 0 ? ((value / totalAssistance.value) * 100).toFixed(1) : '0.0';
          return `${label}: ${value} (${percentage}%)`;
        }
      }
    }
  }
};
</script>

<template>
  <Head title="Admin Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
      <h1 class="text-2xl font-bold">Admin Dashboard</h1>

      <!-- Metrics Cards -->
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
        <div class="rounded-lg bg-white p-6 shadow">
          <h2 class="text-lg font-semibold text-gray-700">Total Users</h2>
          <p class="mt-2 text-3xl font-bold">{{ totalUsers }}</p>
        </div>
        <div class="rounded-lg bg-white p-6 shadow">
          <h2 class="text-lg font-semibold text-gray-700">Citizen Users</h2>
          <p class="mt-2 text-3xl font-bold">{{ citizenUsers }}</p>
        </div>
        <div class="rounded-lg bg-white p-6 shadow">
          <h2 class="text-lg font-semibold text-gray-700">LGU Users</h2>
          <p class="mt-2 text-3xl font-bold">{{ lguUsers }}</p>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="rounded-lg bg-white p-6 shadow">
        <h2 class="text-lg font-semibold text-gray-700">Quick Actions</h2>
        <div class="mt-4 flex gap-4">
          <Link
            href="/admin/users"
            class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
          >
            View All Users
          </Link>
          <Link
            href="/admin/users"
            class="rounded-md bg-green-600 px-4 py-2 text-white hover:bg-green-700"
          >
            Create New User
          </Link>
        </div>
      </div>

      <!-- Assistance Form Chart -->
      <div class="rounded-lg bg-white p-6 shadow">
        <h2 class="text-lg font-semibold text-gray-700">Assistance Requests Distribution</h2>
        <div class="mt-4">
          <div class="mb-4 text-sm text-gray-600">
            Total Assistance Requests: {{ totalAssistance }}
          </div>
          <div class="h-96">
            <Pie :data="chartData" :options="chartOptions" />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>