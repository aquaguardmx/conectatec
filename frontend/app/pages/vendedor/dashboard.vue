<script setup lang="ts">
import SideNavBar from '~/components/vendedor/dashboard/SideNavBar.vue';
import TopNavBar from '../../components/vendedor/dashboard/TopNavBar.vue';

</script>

<template>
    <!-- SideNavBar (Authority: JSON & Design System) -->
    <SideNavBar />
    <!-- Main Canvas Area -->
    <main class="ml-64 min-h-screen">
        <!-- TopNavBar (Authority: JSON) -->
        <TopNavBar />
        <!-- Dashboard Content -->
        <div class="pt-24 pb-12 px-10 max-w-7xl mx-auto space-y-10">
            <!-- Hero Header -->
            <section class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-1">
                    <h2 class="text-4xl font-black tracking-tight text-on-surface">Dashboard</h2>
                    <p class="text-on-surface-variant font-medium">Welcome back, your marketplace performance is
                        up
                        12% this week.</p>
                </div>
                <div class="flex gap-3">
                    <button
                        class="flex items-center gap-2 px-5 py-2.5 bg-secondary-container text-on-secondary-container font-semibold rounded-xl text-sm hover:brightness-95 transition-all">
                        <span class="material-symbols-outlined text-lg">calendar_today</span>
                        Last 30 Days
                    </button>
                    <button
                        class="flex items-center gap-2 px-5 py-2.5 bg-primary text-on-primary font-semibold rounded-xl text-sm hover:shadow-xl hover:-translate-y-0.5 transition-all">
                        <span class="material-symbols-outlined text-lg">download</span>
                        Export Report
                    </button>
                </div>
            </section>
            <!-- Stats Grid (Bento Style) -->
            <section class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Large Stats Card -->
                <div
                    class="md:col-span-2 bg-surface-container-lowest p-8 rounded-xl shadow-[0px_12px_32px_rgba(13,28,46,0.06)] flex flex-col justify-between relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-8">
                            <span class="p-3 bg-primary/10 text-primary rounded-xl">
                                <span class="material-symbols-outlined"
                                    style="font-variation-settings: 'FILL' 1;">payments</span>
                            </span>
                            <span
                                class="text-tertiary font-bold text-sm bg-tertiary-fixed-dim/20 px-3 py-1 rounded-full">+24%
                                vs last month</span>
                        </div>
                        <p class="text-on-surface-variant text-sm font-semibold uppercase tracking-widest">Total
                            Revenue</p>
                        <h3 class="text-5xl font-black tracking-tighter mt-2 text-on-surface">$124,592.00</h3>
                    </div>
                    <!-- Decorative Element -->
                    <div
                        class="absolute -right-10 -bottom-10 w-48 h-48 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors">
                    </div>
                </div>
                <!-- Small Stats Card 1 -->
                <div
                    class="bg-surface-container-lowest p-8 rounded-xl shadow-[0px_12px_32px_rgba(13,28,46,0.06)] hover:bg-surface-container-low transition-colors duration-300">
                    <span
                        class="p-3 bg-secondary-container/50 text-on-secondary-container rounded-xl inline-block mb-6">
                        <span class="material-symbols-outlined">inventory_2</span>
                    </span>
                    <p class="text-on-surface-variant text-sm font-semibold uppercase tracking-widest">Active
                        Products</p>
                    <h3 class="text-4xl font-bold tracking-tight mt-2 text-on-surface">842</h3>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-on-surface-variant">
                        <span class="material-symbols-outlined text-sm">trending_up</span> 12 New today
                    </div>
                </div>
                <!-- Small Stats Card 2 -->
                <div
                    class="bg-surface-container-lowest p-8 rounded-xl shadow-[0px_12px_32px_rgba(13,28,46,0.06)] hover:bg-surface-container-low transition-colors duration-300">
                    <span class="p-3 bg-tertiary/10 text-tertiary rounded-xl inline-block mb-6">
                        <span class="material-symbols-outlined">shopping_bag</span>
                    </span>
                    <p class="text-on-surface-variant text-sm font-semibold uppercase tracking-widest">Pending
                        Orders</p>
                    <h3 class="text-4xl font-bold tracking-tight mt-2 text-on-surface">18</h3>
                    <div class="mt-4 flex items-center gap-1 text-xs font-bold text-error">
                        <span class="material-symbols-outlined text-sm">priority_high</span> 3 Urgent
                    </div>
                </div>
            </section>
            <!-- Main Dashboard Layout: Activities & Analytics -->
            <section class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Recent Activities (Editorial Style) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xl font-bold tracking-tight text-on-surface">Recent Activity</h4>
                        <button class="text-primary font-bold text-sm hover:underline">View History</button>
                    </div>
                    <div class="space-y-4">
                        <!-- Activity Item -->
                        <div
                            class="flex items-center gap-4 p-5 bg-surface-container-lowest rounded-xl hover:shadow-md transition-shadow">
                            <img class="w-14 h-14 rounded-lg object-cover bg-surface-container"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuB-7Xlt7WM7K9Xr8O9I-CjxWbUI2z0rYBQ5ijREKM_jWEyVPXi7sp0CAOy1FEy5Nanv2GGCtIgi8DI7gDYHKRAfpYxSR09RNMTZbM5-ibqGp6Pcp5JzuGJJFONW6YRn4ob9UB1XcDveAoYE28NZMVJbO1wSdI9zVAxTmGUnYOO6DmhVrrJk5mfcc1v9KTX5voLWzs0bhQsqYNMBo26MevBp5bH6droAcpHlBTGZOD5nmZ2izfhsKS708nLsajzlGRkJ1ggZ_VY95Mc" />
                            <div class="flex-1">
                                <p class="text-on-surface font-bold text-sm">Sale: TechNova G-Pro Console</p>
                                <p class="text-on-surface-variant text-xs">Customer: Alex Rivera • 2 mins ago
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-primary font-black">+$599.00</p>
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed-variant rounded-full">Completed</span>
                            </div>
                        </div>
                        <!-- Activity Item -->
                        <div
                            class="flex items-center gap-4 p-5 bg-surface-container-lowest rounded-xl hover:shadow-md transition-shadow">
                            <div
                                class="w-14 h-14 rounded-lg bg-surface-container-high flex items-center justify-center text-primary">
                                <span class="material-symbols-outlined">verified</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-on-surface font-bold text-sm">Product Verified by Admin</p>
                                <p class="text-on-surface-variant text-xs">Precision Workstation Z-2 • 1 hour
                                    ago
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="material-symbols-outlined text-tertiary">check_circle</span>
                            </div>
                        </div>
                        <!-- Activity Item -->
                        <div
                            class="flex items-center gap-4 p-5 bg-surface-container-lowest rounded-xl hover:shadow-md transition-shadow">
                            <img class="w-14 h-14 rounded-lg object-cover bg-surface-container"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBj99f5u4-a0_7YrAB3IO4QajSi_rhyJDJdSh7ytDzgtZLm-iYBPlk-1ydxk-JHFkREgZlXb2fyGAuBJ8wtwWPZ6BY-iKvaTyPBSGRw6vE5NAP9O_3tL2PeP4e8CESS6g2dSnsk5FS4gTysKfjQV0B8nLZaEQovoDnvvR4TDxYHYo_dIxPJrtPEUYcZZOL6FeJmze56AjgoN1Kju9jLXDRP7TjZ7raRC5qwLLWrmYqN7KInz-SRZuEHmRfw8vqoOTRSwVQo2nc1kYI" />
                            <div class="flex-1">
                                <p class="text-on-surface font-bold text-sm">New Review Received</p>
                                <p class="text-on-surface-variant text-xs">"Excellent build quality" • 3 hours
                                    ago
                                </p>
                            </div>
                            <div class="text-right flex items-center gap-0.5 text-primary">
                                <span class="material-symbols-outlined text-xs"
                                    style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="text-xs font-bold">5.0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Performance Breakdown Sidebar -->
                <div class="space-y-6">
                    <h4 class="text-xl font-bold tracking-tight text-on-surface">Store Insights</h4>
                    <div
                        class="bg-surface-container-lowest rounded-xl p-8 space-y-8 shadow-[0px_12px_32px_rgba(13,28,46,0.06)]">
                        <div>
                            <div class="flex justify-between items-end mb-4">
                                <p class="text-sm font-bold text-on-surface">Inventory Health</p>
                                <p class="text-xs font-bold text-primary">82%</p>
                            </div>
                            <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full w-[82%]"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-end mb-4">
                                <p class="text-sm font-bold text-on-surface">Order Fulfillment</p>
                                <p class="text-xs font-bold text-tertiary">96%</p>
                            </div>
                            <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                                <div class="h-full bg-tertiary rounded-full w-[96%]"></div>
                            </div>
                        </div>
                        <div class="pt-4 space-y-4">
                            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Top
                                Categories</p>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    class="px-3 py-1.5 bg-secondary-container text-on-secondary-container rounded-full text-[10px] font-black uppercase">Workstations</span>
                                <span
                                    class="px-3 py-1.5 bg-surface-container text-on-surface-variant rounded-full text-[10px] font-black uppercase">Components</span>
                                <span
                                    class="px-3 py-1.5 bg-surface-container text-on-surface-variant rounded-full text-[10px] font-black uppercase">Accessories</span>
                                <span
                                    class="px-3 py-1.5 bg-surface-container text-on-surface-variant rounded-full text-[10px] font-black uppercase">Networking</span>
                            </div>
                        </div>
                        <div class="bg-primary/5 p-4 rounded-xl border border-primary/10">
                            <p class="text-xs font-bold text-primary mb-1">PRO TIP</p>
                            <p class="text-xs leading-relaxed text-on-surface">Updating product images can boost
                                conversions by up to 25% this quarter.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

</template>
