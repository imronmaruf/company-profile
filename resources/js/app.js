// Import Bootstrap dan dependensi lainnya
// import "./bootstrap";
import "@tabler/icons-webfont/dist/tabler-icons.css";

// Import DataTables
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";
import DataTable from "datatables.net-bs5";

// Import SweetAlert2
import Swal from "sweetalert2";
window.Swal = Swal;

// Inisialisasi DataTable
document.addEventListener("DOMContentLoaded", () => {
    const table = document.querySelector("#dataTable");
    if (table) {
        new DataTable(table);
    }
});
