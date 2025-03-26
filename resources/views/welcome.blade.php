<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi des Stocks en Temps Réel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">📊 Suivi des Stocks</h2>

        <div id="stock-chart" class="w-full h-80 mb-6"></div>

        <h3 class="text-lg font-semibold text-gray-700">📦 Produits en Stock</h3>
        <table class="min-w-full bg-white border border-gray-200 rounded-md shadow-md mt-4">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">Produit</th>
                    <th class="py-2 px-4 border">Quantité</th>
                    <th class="py-2 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody id="stock-table"></tbody>
        </table>

        <h3 class="mt-6 text-lg font-semibold text-gray-700">➕ Ajouter un Produit</h3>
        <form id="stock-form" class="mt-4">
            <input type="text" id="product_name" placeholder="Nom du produit" class="border p-2 rounded w-full mb-2" required>
            <input type="number" id="quantity" placeholder="Quantité" class="border p-2 rounded w-full mb-2" required>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Ajouter</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let stocks = [];

            const stockTable = document.getElementById("stock-table");
            const stockForm = document.getElementById("stock-form");
            const chartContainer = document.getElementById("stock-chart");

            const chart = Highcharts.chart(chartContainer, {
                chart: { type: "column" },
                title: { text: "Stock des Produits" },
                xAxis: { categories: [] },
                yAxis: { title: { text: "Quantité" } },
                series: [{ name: "Produits", data: [] }]
            });

            // Fonction pour charger les stocks depuis l'API
            const fetchStocks = async () => {
                const response = await fetch("/api/stocks");
                stocks = await response.json();
                updateUI();
            };

            // Fonction pour mettre à jour l'UI (Tableau + Graphique)
            const updateUI = () => {
                stockTable.innerHTML = "";
                const categories = [];
                const data = [];

                stocks.forEach(stock => {
                    categories.push(stock.product_name);
                    data.push(stock.quantity);

                    stockTable.innerHTML += `
                        <tr class="border">
                            <td class="py-2 px-4 border">${stock.product_name}</td>
                            <td class="py-2 px-4 border">${stock.quantity}</td>
                            <td class="py-2 px-4 border">
                                <button onclick="deleteStock(${stock.id})" class="bg-red-500 text-white px-3 py-1 rounded">🗑 Supprimer</button>
                            </td>
                        </tr>
                    `;
                });

                chart.xAxis[0].setCategories(categories);
                chart.series[0].setData(data);
            };

            // Ajouter un produit
            stockForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                const product_name = document.getElementById("product_name").value;
                const quantity = document.getElementById("quantity").value;

                const response = await fetch("/api/stocks", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ product_name, quantity })
                });

                if (response.ok) {
                    document.getElementById("product_name").value = "";
                    document.getElementById("quantity").value = "";
                }
            });

            // Supprimer un produit
            window.deleteStock = async (id) => {
                await fetch(`/api/stocks/${id}`, { method: "DELETE" });
            };

            // Intégrer Pusher (ou Reverb si configuré)

            window.Echo.channel('stocks')
                .listen('StockUpdated', (data) => {
                    stocks = stocks.map(stock => stock.id === data.stock.id ? data.stock : stock);
                    if (!stocks.find(stock => stock.id === data.stock.id)) {
                        stocks.push(data.stock);
                    }
                    updateUI();
                });

            // Charger les stocks initiaux
            fetchStocks();
        });
    </script>
</body>
</html>
