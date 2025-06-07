<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Form',
        href: '/user/form',
    },
];
</script>

<template>
    <Head title="Form" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 rounded-xl p-6 bg-white shadow-md">
            <h1 class="text-2xl font-bold">General Intake Sheet</h1>

            <!-- Section A -->
            <section>
                <h2 class="font-semibold">A. Client Identifying Information:</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <input type="text" placeholder="Client Name" class="input" />
                    <div class="flex gap-2 items-center">
                        <label>Sex:</label>
                        <label><input type="radio" name="clientSex" /> Male</label>
                        <label><input type="radio" name="clientSex" /> Female</label>
                    </div>
                    <input type="date" placeholder="Date of Birth" class="input" />
                    <input type="text" placeholder="Present Address" class="input" />
                    <input type="text" placeholder="Relationship to Beneficiary" class="input" />
                    <select class="input">
                        <option>Status</option>
                        <option>Single</option>
                        <option>Married</option>
                    </select>
                    <input type="text" placeholder="Nationality" class="input" />
                    <input type="text" placeholder="Occupation" class="input" />
                    <input type="text" placeholder="Monthly Income" class="input" />
                    <input type="text" placeholder="Religion" class="input" />
                </div>
            </section>

            <!-- Section B -->
            <section>
                <h2 class="font-semibold">B. Beneficiary Identifying Information:</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <input type="text" placeholder="Beneficiary Name" class="input" />
                    <input type="text" placeholder="Place of Birth" class="input" />
                    <input type="date" placeholder="Date of Birth" class="input" />
                    <div class="flex gap-2 items-center">
                        <label>Sex:</label>
                        <label><input type="radio" name="beneficiarySex" /> Male</label>
                        <label><input type="radio" name="beneficiarySex" /> Female</label>
                    </div>
                </div>
            </section>

            <!-- Section C -->
            <section>
                <h2 class="font-semibold">C. Beneficiary Family Composition:</h2>
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border border-gray-300 mt-2">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-2 py-1">Full Name</th>
                                <th class="border px-2 py-1">Sex</th>
                                <th class="border px-2 py-1">Birthdate</th>
                                <th class="border px-2 py-1">Status</th>
                                <th class="border px-2 py-1">Occupation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="i in 3" :key="i">
                                <td class="border px-2 py-1"><input type="text" class="input w-full" /></td>
                                <td class="border px-2 py-1"><input type="text" class="input w-full" /></td>
                                <td class="border px-2 py-1"><input type="date" class="input w-full" /></td>
                                <td class="border px-2 py-1"><input type="text" class="input w-full" /></td>
                                <td class="border px-2 py-1"><input type="text" class="input w-full" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Section D -->
            <section>
                <h2 class="font-semibold">D. Assessment:</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                    <textarea placeholder="1. Problem Presented" class="input h-24"></textarea>
                    <textarea placeholder="2. Social Worker Assistance" class="input h-24"></textarea>
                    <div>
                        <p class="font-medium mb-1">3. Client Category (Check Only):</p>
                        <div class="flex flex-col gap-1">
                            <label><input type="checkbox" /> Children in need of Protection</label>
                            <label><input type="checkbox" /> Youth in need of Protection</label>
                            <label><input type="checkbox" /> Women in Specially Difficult Circumstances</label>
                            <label><input type="checkbox" /> PWD</label>
                            <label><input type="checkbox" /> Senior Citizen</label>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section E -->
            <section>
                <h2 class="font-semibold">E. Recommended Services and Assistance:</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div>
                        <p class="mb-2 font-medium">1. Nature of Service/Assistance:</p>
                        <label><input type="checkbox" /> Counseling</label><br />
                        <label><input type="checkbox" /> Legal Assistance</label><br />
                        <label><input type="checkbox" /> Referral: </label>
                        <input type="text" placeholder="Specify" class="input mt-1 w-full" />
                    </div>

                    <div>
                        <p class="font-medium mb-1">Financial Assistance:</p>
                        <div class="grid grid-cols-2 gap-2">
                            <label><input type="checkbox" /> Food Subsidy</label>
                            <label><input type="checkbox" /> Livelihood</label>
                            <label><input type="checkbox" /> Educational</label>
                            <label><input type="checkbox" /> Medical</label>
                            <label><input type="checkbox" /> Burial</label>
                            <label><input type="checkbox" /> Transportation</label>
                        </div>
                        <input type="text" placeholder="Value / Pesos" class="input mt-2 w-full" />
                        <input type="text" placeholder="Amount to be extended" class="input mt-2 w-full" />

                        <p class="font-medium mt-2">Mode of Financial Assistance:</p>
                        <label><input type="checkbox" /> Cash</label><br />
                        <label><input type="checkbox" /> Check</label><br />
                        <label><input type="checkbox" /> Guarantee Letter</label>

                        <p class="font-medium mt-2">Source of Assistance:</p>
                        <label><input type="checkbox" /> Regular Funds</label><br />
                        <label><input type="checkbox" /> Donation</label><br />
                        <label><input type="checkbox" /> Legislator</label><br />
                        <label><input type="checkbox" /> Others:</label>
                        <input type="text" placeholder="Specify" class="input mt-1 w-full" />
                    </div>
                </div>

                <div class="mt-4">
                    <p class="font-medium">Material Assistance:</p>
                    <label><input type="checkbox" /> Food Packs</label><br />
                    <label><input type="checkbox" /> Used Clothing</label><br />
                    <label><input type="checkbox" /> Assistive Device</label><br />
                    <label><input type="checkbox" /> Others:</label>
                    <input type="text" placeholder="Specify" class="input mt-1 w-full" />
                </div>
            </section>

            <!-- Footer -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
               
                <div class="text-right self-end">
                    <p class="italic font-semibold">Checked by:</p>
                    <p>Marc Stephen S. Dao, RSW</p>
                    <p class="text-sm text-gray-500">(Signature of Social Worker)</p>
                </div>
            </section>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
