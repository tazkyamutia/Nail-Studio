<?php
// config/database.php
class Database {
    private $host = 'localhost';
    private $db_name = 'nail_shop_db';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// classes/DashboardAnalytics.php
class DashboardAnalytics {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Get sales data for the last 12 months
    public function getSalesData() {
        $query = "SELECT 
                    MONTH(order_date) as month,
                    MONTHNAME(order_date) as month_name,
                    SUM(total_amount) as total_sales
                  FROM orders 
                  WHERE order_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                  AND status = 'completed'
                  GROUP BY MONTH(order_date), MONTHNAME(order_date)
                  ORDER BY MONTH(order_date)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $salesData = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                  'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        // Initialize all months with 0
        foreach($months as $month) {
            $salesData[$month] = 0;
        }
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $monthName = substr($row['month_name'], 0, 3);
            $salesData[$monthName] = (float)$row['total_sales'];
        }
        
        return $salesData;
    }
    
    // Get visitor statistics for the last 7 days
    public function getVisitorStats() {
        $query = "SELECT 
                    DAYNAME(visit_date) as day_name,
                    COUNT(*) as visitor_count
                  FROM visitor_logs 
                  WHERE visit_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                  GROUP BY DAYOFWEEK(visit_date), DAYNAME(visit_date)
                  ORDER BY DAYOFWEEK(visit_date)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $visitorData = [];
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        
        // Initialize all days with 0
        foreach($days as $day) {
            $visitorData[$day] = 0;
        }
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dayName = substr($row['day_name'], 0, 3);
            $visitorData[$dayName] = (int)$row['visitor_count'];
        }
        
        return $visitorData;
    }
    
    // Get category performance data
    public function getCategoryPerformance() {
        $query = "SELECT 
                    c.name as category_name,
                    COUNT(oi.product_id) as total_sold,
                    SUM(oi.quantity * oi.price) as total_revenue
                  FROM categories c
                  LEFT JOIN product p ON c.id = p.category_id
                  LEFT JOIN order_items oi ON p.id = oi.product_id
                  LEFT JOIN orders o ON oi.order_id = o.id
                  WHERE o.status = 'completed'
                  AND o.order_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                  GROUP BY c.id, c.name
                  ORDER BY total_revenue DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $categoryData = [];
        $totalRevenue = 0;
        
        // First pass: calculate total revenue
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $totalRevenue += (float)$row['total_revenue'];
            $categoryData[] = $row;
        }
        
        // Second pass: calculate percentages
        $performanceData = [];
        foreach($categoryData as $category) {
            $percentage = $totalRevenue > 0 ? 
                round(((float)$category['total_revenue'] / $totalRevenue) * 100, 1) : 0;
            
            $performanceData[] = [
                'name' => $category['category_name'],
                'percentage' => $percentage,
                'revenue' => (float)$category['total_revenue'],
                'items_sold' => (int)$category['total_sold']
            ];
        }
        
        return $performanceData;
    }
    
    // Get order status distribution
    public function getOrderStatus() {
        $query = "SELECT 
                    status,
                    COUNT(*) as count
                  FROM orders 
                  WHERE order_date >= DATE_SUB(NOW(), INTERVAL 3 MONTH)
                  GROUP BY status";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $statusData = [];
        $totalOrders = 0;
        
        // First pass: count total orders
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $totalOrders += (int)$row['count'];
            $statusData[] = $row;
        }
        
        // Second pass: calculate percentages
        $orderStatusData = [];
        foreach($statusData as $status) {
            $percentage = $totalOrders > 0 ? 
                round(((int)$status['count'] / $totalOrders) * 100, 1) : 0;
            
            $orderStatusData[] = [
                'status' => ucfirst($status['status']),
                'percentage' => $percentage,
                'count' => (int)$status['count']
            ];
        }
        
        return $orderStatusData;
    }
    
    // Get dashboard summary metrics
    public function getDashboardSummary() {
        $summaryData = [];
        
        // Total revenue this month
        $query = "SELECT SUM(total_amount) as monthly_revenue 
                  FROM orders 
                  WHERE MONTH(order_date) = MONTH(NOW()) 
                  AND YEAR(order_date) = YEAR(NOW())
                  AND status = 'completed'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $summaryData['monthly_revenue'] = (float)($row['monthly_revenue'] ?? 0);
        
        // Total orders today
        $query = "SELECT COUNT(*) as daily_orders 
                  FROM orders 
                  WHERE DATE(order_date) = CURDATE()";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $summaryData['daily_orders'] = (int)$row['daily_orders'];
        
        // Active products
        $query = "SELECT COUNT(*) as active_products 
                  FROM product
                  WHERE status = 'active'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $summaryData['active_products'] = (int)$row['active_products'];
        
        // Total customers
        $query = "SELECT COUNT(*) as total_customers 
                  FROM customers 
                  WHERE status = 'active'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $summaryData['total_customers'] = (int)$row['total_customers'];
        
        return $summaryData;
    }
}

// api/dashboard_data.php
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';
require_once '../classes/DashboardAnalytics.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    $analytics = new DashboardAnalytics($db);
    
    $action = $_GET['action'] ?? '';
    
    switch($action) {
        case 'sales':
            $data = $analytics->getSalesData();
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'visitors':
            $data = $analytics->getVisitorStats();
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'categories':
            $data = $analytics->getCategoryPerformance();
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'orders':
            $data = $analytics->getOrderStatus();
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'summary':
            $data = $analytics->getDashboardSummary();
            echo json_encode(['success' => true, 'data' => $data]);
            break;
            
        case 'all':
            $dashboardData = [
                'sales' => $analytics->getSalesData(),
                'visitors' => $analytics->getVisitorStats(),
                'categories' => $analytics->getCategoryPerformance(),
                'orders' => $analytics->getOrderStatus(),
                'summary' => $analytics->getDashboardSummary()
            ];
            echo json_encode(['success' => true, 'data' => $dashboardData]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
    
} catch(Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

// js/dashboard.js
class DashboardCharts {
    constructor() {
        this.charts = {};
        this.colors = {
            primary: '#3498db',
            success: '#2ecc71',
            warning: '#f39c12',
            danger: '#e74c3c',
            info: '#17a2b8',
            secondary: '#6c757d',
            nail_polish: '#8B1538',
            nail_tools: '#B85450',
            nail_care: '#C4405C',
            nail_art: '#E8A4A0'
        };
        this.init();
    }
    
    async init() {
        this.showLoading();
        try {
            await this.loadDashboardData();
            this.hideLoading();
        } catch (error) {
            console.error('Dashboard initialization failed:', error);
            this.showError('Failed to load dashboard data');
        }
    }
    
    showLoading() {
        document.querySelectorAll('.chart-card').forEach(card => {
            card.innerHTML = `
                <h3>${card.querySelector('h3')?.textContent || 'Loading...'}</h3>
                <div class="chart-loading">
                    <div class="spinner"></div>
                    <span>Loading data...</span>
                </div>
            `;
        });
    }
    
    hideLoading() {
        // Restore canvas elements
        document.querySelectorAll('.chart-card').forEach((card, index) => {
            const titles = ['Sales Analytics', 'Visitor Statistics', 'Category Performance', 'Order Status'];
            const canvasIds = ['salesChart', 'visitorChart', 'productChart', 'orderChart'];
            
            card.innerHTML = `
                <h3>${titles[index]}</h3>
                <canvas id="${canvasIds[index]}"></canvas>
            `;
        });
    }
    
    showError(message) {
        document.querySelectorAll('.chart-card').forEach(card => {
            card.innerHTML = `
                <h3>Error</h3>
                <div class="error-message" style="text-align: center; color: #e74c3c; padding: 50px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>${message}</p>
                    <button onclick="location.reload()" class="retry-btn">Retry</button>
                </div>
            `;
        });
    }
    
    async loadDashboardData() {
        try {
            const response = await fetch('../api/dashboard_data.php?action=all');
            const result = await response.json();
            
            if (result.success) {
                this.createSalesChart(result.data.sales);
                this.createVisitorChart(result.data.visitors);
                this.createCategoryChart(result.data.categories);
                this.createOrderChart(result.data.orders);
                this.updateSummaryCards(result.data.summary);
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error loading dashboard data:', error);
            // Fallback to demo data
            this.loadDemoData();
        }
    }
    
    loadDemoData() {
        // Demo data as fallback
        const demoData = {
            sales: {
                'Jan': 12000, 'Feb': 15000, 'Mar': 10000, 'Apr': 18000,
                'May': 22000, 'Jun': 25000, 'Jul': 20000, 'Aug': 28000,
                'Sep': 30000, 'Oct': 26000, 'Nov': 32000, 'Dec': 35000
            },
            visitors: {
                'Mon': 1200, 'Tue': 1500, 'Wed': 1300, 'Thu': 1800,
                'Fri': 2000, 'Sat': 2200, 'Sun': 1600
            },
            categories: [
                {name: 'Nail Polish', percentage: 35},
                {name: 'Nail Tools', percentage: 28},
                {name: 'Nail Care', percentage: 22},
                {name: 'Nail Art Kits', percentage: 15}
            ],
            orders: [
                {status: 'Completed', percentage: 65},
                {status: 'Processing', percentage: 20},
                {status: 'Pending', percentage: 10},
                {status: 'Cancelled', percentage: 5}
            ]
        };
        
        this.createSalesChart(demoData.sales);
        this.createVisitorChart(demoData.visitors);
        this.createCategoryChart(demoData.categories);
        this.createOrderChart(demoData.orders);
    }
    
    createSalesChart(data) {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        this.charts.sales = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    label: 'Sales ($)',
                    data: Object.values(data),
                    borderColor: this.colors.primary,
                    backgroundColor: this.colors.primary + '20',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: this.colors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return 'Sales: $' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        },
                        grid: {
                            color: '#f8f9fa'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    }
    
    createVisitorChart(data) {
        const ctx = document.getElementById('visitorChart').getContext('2d');
        
        this.charts.visitors = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    label: 'Visitors',
                    data: Object.values(data),
                    backgroundColor: [
                        this.colors.danger, this.colors.warning, this.colors.info,
                        this.colors.success, this.colors.primary, this.colors.secondary,
                        this.colors.nail_polish
                    ],
                    borderColor: '#fff',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Visitors: ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f8f9fa'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    
    createCategoryChart(data) {
        const ctx = document.getElementById('productChart').getContext('2d');
        
        const labels = data.map(item => item.name);
        const values = data.map(item => item.percentage);
        const colors = [this.colors.nail_polish, this.colors.nail_tools, 
                       this.colors.nail_care, this.colors.nail_art];
        
        this.charts.categories = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors.slice(0, labels.length),
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverOffset: 10,
                    hoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                },
                cutout: '60%',
                animation: {
                    animateRotate: true,
                    animateScale: true
                }
            }
        });
    }
    
    createOrderChart(data) {
        const ctx = document.getElementById('orderChart').getContext('2d');
        
        const labels = data.map(item => item.status);
        const values = data.map(item => item.percentage);
        const colors = [this.colors.success, this.colors.warning, 
                       this.colors.info, this.colors.danger];
        
        this.charts.orders = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors.slice(0, labels.length),
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverOffset: 8,
                    hoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true
                }
            }
        });
    }
    
    updateSummaryCards(data) {
        // This method can be used to update summary cards if you have them
        if (data) {
            console.log('Summary data:', data);
            // You can add summary cards to your dashboard here
        }
    }
    
    // Method to refresh all charts
    async refreshCharts() {
        this.showLoading();
        await this.loadDashboardData();
    }
    
    // Method to destroy all charts (useful for cleanup)
    destroyCharts() {
        Object.values(this.charts).forEach(chart => {
            if (chart) chart.destroy();
        });
        this.charts = {};
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.dashboard = new DashboardCharts();
    
    // Auto-refresh every 5 minutes
    setInterval(() => {
        window.dashboard.refreshCharts();
    }, 300000);
});

// Handle window resize
window.addEventListener('resize', function() {
    if (window.dashboard && window.dashboard.charts) {
        Object.values(window.dashboard.charts).forEach(chart => {
            if (chart) chart.resize();
        });
    }
});

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DashboardCharts;
}
?>