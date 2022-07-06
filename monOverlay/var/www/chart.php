<!doctype html>
<?php
        $nfields = 2;
        $api = 'mesures_json';
        $title = 'Température';
        $field = 'temperature';
        $title2 = 'Pression';
        $field2 = 'pression';
$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
 
$fetch_url = '/' . $api . '/?' . $uri_parts[1];
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sonde@tmo</title>
        <style>
        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }
        .chartMenu {
            width: 100vw;
            height: 40px;
            background: #1A1A1A;
            color: rgba(255, 0, 0, 1);
        }
        .chartMenu p {
            padding: 10px;
            font-size: 20px;
        }
        .chartCard {
            width: 100vw;
            height: calc(100vh - 40px);
            background: rgba(150, 150, 150, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .chartBox {
            width: 700px;
            padding: 20px;
            border-radius: 5px;
            border: solid 1px rgba(255, 0, 0, 1);
            background: white;
        }
        </style>
    </head>
    <body onload="updateChart()">
        <div class="chartMenu">
            <p>Sonde@tmo</p>
        </div>
        <div class="chartCard">
            <div class="chartBox">
                <canvas id="myChart"></canvas>
                <button onclick="updateChart()">Refresh</button>
            </div>
        </div>
        <script type="text/javascript" src="/js/chart.js"></script>
        <script>
        // fetch block
        function updateChart() {
            async function fetchData() {
                const url = '<?php echo $fetch_url; ?>';
                const response = await fetch(url);
                //wait until the request has been completed
                const datapoints = await response.json();
                //console.log(datapoints);
                return datapoints;
            };
 
            fetchData().then(datapoints => {
                const xDate = datapoints.map(
                    function(index) { return index.date; }
                )
 
                const yValues = datapoints.map(
                    function(index) { return index.<?php echo $field; ?>; }
                )
<?php if ($nfields > 1) {
echo "
                const yValues2 = datapoints.map(
                    function(index) { return index.$field2; }
                )
";
} ?>
 
                //console.log(xDate);
                //console.log(yValues);
 
                myChart.config.data.labels = xDate;
 
                myChart.config.data.datasets[0].data = yValues;
                myChart.config.data.datasets[0].label = "<?php echo $title; ?>";
<?php if ($nfields > 1) {
echo "
                myChart.config.data.datasets[1].data = yValues2;
                myChart.config.data.datasets[1].label = \"$title2\";
";
} ?>
 
                myChart.update();
            });
        }
 
        // setup
        const data = {
            labels: ['Loading...'],
            datasets: [{
                label: '',
                borderColor: 'rgb(255, 0, 0)',
                data: [],
                borderWidth: 1,
                yAxisID: 'y'
            }
<?php if ($nfields > 1) {
echo "          ,
            {
                label: 'other',
                borderColor: 'rgb(0, 0, 255)',
                data: [],
                borderWidth: 1,
                yAxisID: 'y1'
            }
";
} ?>
            ]
        };
 
        // config
        const config = {
            type: 'line',
            data,
            options: {
                scales: {
                    y: {
                        beginAtZero: false
                    }
<?php if ($nfields > 1) {
echo "                  ,
                    y1: {
                        beginAtZero: false
                    }
";
} ?>
                }
            }
        };
 
        // render init block
        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
        </script>
    </body>
</html>
