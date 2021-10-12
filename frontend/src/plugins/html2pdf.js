import Vue from 'vue'
import html2pdf from "html2pdf.js";
// import html2PDF from 'jspdf-html2canvas';
// import VueHtml2pdf from 'vue-html2pdf'

console.log('run html2');

if (process.client) {
  console.log('run html2');
  Vue.component(html2pdf);
  console.log('run html2');
}
