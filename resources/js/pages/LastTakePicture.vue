<template>
    <div>
        <h1>Data akan di-update setiap 5 detik</h1>
        <div v-if="data">
            <pre>{{ data }}</pre>
        </div>
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'

export default {
    data() {
        return {
            data: null,  // untuk menyimpan data
        }
    },
    mounted() {
        // Memanggil data pertama kali saat halaman dimuat
        this.fetchData()

        // Set interval untuk update data setiap 5 detik
        setInterval(() => {
            this.fetchData()
        }, 5000); // 5000 ms = 5 detik
    },
    methods: {
        fetchData() {
            // Menggunakan Inertia.get untuk mendapatkan data terbaru tanpa mereload halaman
            Inertia.get('/api/data', {}, {
                preserveState: true,  // untuk menjaga state lainnya di halaman
                replace: true         // mengganti data tanpa mereload seluruh halaman
            }).then(response => {
                // Misalnya menyimpan data ke dalam state Vue
                this.data = response.props.data;
            });
        }
    }
}
</script>