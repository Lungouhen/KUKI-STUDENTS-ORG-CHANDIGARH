(() => {
    if (typeof window.ApexCharts !== 'function') {
        return;
    }

    const membersChart = document.getElementById('membersChart');
    const donationsChart = document.getElementById('donationsChart');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];

    if (membersChart) {
        new window.ApexCharts(membersChart, {
            series: [{ name: 'New Registrations', data: [12, 19, 25, 30, 42, 58, 75] }],
            chart: { type: 'area', height: 230, toolbar: { show: false } },
            colors: ['#003566'],
            stroke: { curve: 'smooth', width: 3 },
            fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.05 } },
            xaxis: { categories: months },
        }).render();
    }

    if (donationsChart) {
        new window.ApexCharts(donationsChart, {
            series: [{ name: 'Welfare Funds (₹)', data: [15000, 22000, 18000, 35000, 48000, 52000, 65000] }],
            chart: { type: 'bar', height: 230, toolbar: { show: false } },
            colors: ['#0d9488'],
            plotOptions: { bar: { borderRadius: 6, columnWidth: '45%' } },
            xaxis: { categories: months },
        }).render();
    }
})();
