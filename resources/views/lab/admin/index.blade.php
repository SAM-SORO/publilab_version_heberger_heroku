@extends('baseAdmin')

@section('title', 'Tableau de bord administrateur')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- En-tête du tableau de bord -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt mr-2"></i>Tableau de bord
        </h1>
        <div>
            <span class="text-muted">Dernière mise à jour: {{ now()->format('d/m/Y à H:i') }}</span>
            <button class="btn btn-sm btn-outline-primary ml-2" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <!-- Statistiques principales - Première ligne -->
    <div class="row">
        <!-- Chercheurs -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Chercheurs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalChercheurs }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.listeChercheurs') }}" class="text-success small">
                        Voir tous <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Doctorants -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Doctorants</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDoctorants }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.listeDoctorants') }}" class="text-success small">
                        Voir tous <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Articles -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Articles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalArticles }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.listeArticles') }}" class="text-success small">
                        Voir tous <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Publications -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Publications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPublications }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.listePublications') }}" class="text-success small">
                        Voir toutes<i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques principales - Deuxième ligne -->
    <div class="row">
        <!-- UMRI -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                UMRI</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUmris }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-university fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.listeUmris') }}" class="text-success small">
                        Voir toutes <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Laboratoires -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Laboratoires</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalLaboratoires }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-flask fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.listeLaboratoires') }}" class="text-success small">
                        Voir tous <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Axes de recherche -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Axes de recherche</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAxeRecherche }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-compass fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.listeAxeRecherche') }}" class="text-success small">
                        Voir tous <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Thèmes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Thèmes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalThemes }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-lightbulb fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.listeTheme') }}" class="text-success small">
                        Voir tous <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques principales - Troisième ligne -->
    <div class="row">
        <!-- Grades -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Grades</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalGrades }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-award fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.listeGrade') }}" class="text-success small">
                        Voir tous <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bases d'indexation -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Bases d'indexation</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBdIndexation }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.listeBaseIndexation') }}" class="text-success small">
                        Voir toutes <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Types d'articles -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Types d'articles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTypeArticles }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.listeTypeArticle') }}" class="text-success small">
                        Voir tous <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Types de publications -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class=" text-sm-left font-weight-bold text-success text-uppercase mb-1">
                                Types publications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTypePublications }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-open fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('admin.listeTypePublications') }}" class="text-success small">
                        Voir tous <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par UMRI -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar"></i> Statistiques par UMRI
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Catégorie</th>
                                    @foreach($statsParUMRI as $stat)
                                        <th class="text-center">
                                            <strong>{{ $stat->sigleUMRI }}</strong>
                                        </th>
                                    @endforeach
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Ligne Chercheurs -->
                                <tr>
                                    <td><i class="fas fa-user-tie text-primary mr-2"></i>Chercheurs</td>
                                    @foreach($statsParUMRI as $stat)
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ $stat->total_chercheurs }}</span>
                                        </td>
                                    @endforeach
                                    <td class="text-center"><strong>{{ $totalChercheurs }}</strong></td>
                                </tr>

                                <!-- Ligne Doctorants -->
                                <tr>
                                    <td><i class="fas fa-user-graduate text-success mr-2"></i>Doctorants</td>
                                    @foreach($statsParUMRI as $stat)
                                        <td class="text-center">
                                            <span class="badge badge-success">{{ $stat->total_doctorants }}</span>
                                        </td>
                                    @endforeach
                                    <td class="text-center"><strong>{{ $totalDoctorants }}</strong></td>
                                </tr>

                                <!-- Ligne Articles -->
                                <tr>
                                    <td><i class="fas fa-file-alt text-warning mr-2"></i>Articles</td>
                                    @foreach($statsParUMRI as $stat)
                                        <td class="text-center">
                                            <span class="badge badge-primary">{{ $stat->total_articles }}</span>
                                        </td>
                                    @endforeach
                                    <td class="text-center"><strong>{{ $totalArticles }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique des articles par année -->
    <div class="row mt-4">
        <div class="col-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-light">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar mr-1"></i> Publications par année
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Options:</div>
                            <a class="dropdown-item" href="#" id="showLastFiveYears">5 dernières années</a>
                            <a class="dropdown-item" href="#" id="showAllYears">Toutes les années</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" id="downloadChartImage">Télécharger l'image</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="articlesYearlyChart" height="320"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique des articles par année
    const yearlyData = @json($articlesParAnnee);
    const years = Object.keys(yearlyData);
    const counts = Object.values(yearlyData);

    // Configuration du graphique des articles par année
    const yearlyCtx = document.getElementById('articlesYearlyChart').getContext('2d');
    const yearlyChart = new Chart(yearlyCtx, {
        type: 'bar',
        data: {
            labels: years,
            datasets: [{
                label: 'Nombre d\'articles',
                data: counts,
                backgroundColor: 'rgba(78, 115, 223, 0.7)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        title: function(tooltipItems) {
                            return 'Année: ' + tooltipItems[0].label;
                        },
                        label: function(context) {
                            return context.raw + ' article' + (context.raw > 1 ? 's' : '');
                        }
                    }
                }
            }
        }
    });

    // Filtrer pour afficher les 5 dernières années
    document.getElementById('showLastFiveYears').addEventListener('click', function(e) {
        e.preventDefault();
        const lastFiveYears = years.slice(-5);
        yearlyChart.data.labels = lastFiveYears;
        yearlyChart.data.datasets[0].data = lastFiveYears.map(year => yearlyData[year]);
        yearlyChart.update();
    });

    // Afficher toutes les années
    document.getElementById('showAllYears').addEventListener('click', function(e) {
        e.preventDefault();
        yearlyChart.data.labels = years;
        yearlyChart.data.datasets[0].data = counts;
        yearlyChart.update();
    });

    // Télécharger l'image du graphique
    document.getElementById('downloadChartImage').addEventListener('click', function(e) {
        e.preventDefault();
        const link = document.createElement('a');
        link.download = 'articles-par-annee.png';
        link.href = document.getElementById('articlesYearlyChart').toDataURL('image/png');
        link.click();
    });
});
</script>
@endsection

@section('styles')
<style>
.border-left-primary {
    border-left: 4px solid #4e73df;
}
.border-left-success {
    border-left: 4px solid #1cc88a;
}
.border-left-info {
    border-left: 4px solid #36b9cc;
}
.border-left-warning {
    border-left: 4px solid #f6c23e;
}
.border-left-danger {
    border-left: 4px solid #e74a3b;
}
.border-left-secondary {
    border-left: 4px solid #858796;
}
.border-left-dark {
    border-left: 4px solid #5a5c69;
}
.chart-area {
    position: relative;
    height: 320px;
    width: 100%;
}
</style>
@endsection
