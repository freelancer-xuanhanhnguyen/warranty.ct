/*
 *  Document   : be_pages_dashboard.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Dashboard Page
 */

// Chart.js Charts, for more examples you can check out http://www.chartjs.org/docs
class pageDashboard {
  /*
   * Init Charts
   *
   */
  static initCharts() {
    // Set Global Chart.js configuration
    Chart.defaults.color = '#818d96';
    Chart.defaults.scale.grid.lineWidth = 0;
    Chart.defaults.scale.beginAtZero = true;
    Chart.defaults.datasets.bar.maxBarThickness = 45;
    Chart.defaults.elements.bar.borderRadius = 4;
    Chart.defaults.elements.bar.borderSkipped = false;
    Chart.defaults.elements.point.radius = 0;
    Chart.defaults.elements.point.hoverRadius = 0;
    Chart.defaults.plugins.tooltip.radius = 3;
    Chart.defaults.plugins.legend.labels.boxWidth = 10;

    // Get Chart Containers
    let chartCreated = document.getElementById('js-chartjs-created');
    let chartCompleted = document.getElementById('js-chartjs-completed');
    let chartTotalOrdersCon = document.getElementById('js-chartjs-total-orders');
    let chartTotalEarningsCon = document.getElementById('js-chartjs-total-earnings');
    let chartNewCustomersCon = document.getElementById('js-chartjs-new-customers');

    // Set Chart and Chart Data variables
    let chartEarnings, chartTotalOrders, chartTotalEarnings, chartNewCustomers;
    // Init Chart Earnings
    if (chartCreated !== null) {
      new Chart(chartCreated, {
        type: 'bar',
        data: {
          labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'],
          datasets: [
            {
              label: 'Tuần trước',
              fill: true,
              backgroundColor: 'rgba(100, 116, 139, .15)',
              borderColor: 'transparent',
              pointBackgroundColor: 'rgba(100, 116, 139, 1)',
              pointBorderColor: '#fff',
              pointHoverBackgroundColor: '#fff',
              pointHoverBorderColor: 'rgba(100, 116, 139, 1)',
              data: stats?.created?.last ?? []
            },
            {
              label: 'Tuần này',
              fill: true,
              backgroundColor: 'rgba(100, 116, 139, .7)',
              borderColor: 'transparent',
              pointBackgroundColor: 'rgba(100, 116, 139, 1)',
              pointBorderColor: '#fff',
              pointHoverBackgroundColor: '#fff',
              pointHoverBorderColor: 'rgba(100, 116, 139, 1)',
              data: stats?.created?.today ?? []
            },
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: {
              display: false,
              grid: {
                drawBorder: false
              }
            },
            y: {
              display: false,
              grid: {
                drawBorder: false
              }
            }
          },
          interaction: {
            intersect: false,
          },
          plugins: {
            legend: {
              labels: {
                boxHeight: 10,
                font: {
                  size: 14
                }
              }
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  return ' ' + context.parsed.y + ' phiếu mới';
                }
              }
            }
          }
        }
      });
    }

    if (chartCompleted !== null) {
      new Chart(chartCompleted, {
        type: 'bar',
        data: {
          labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'],
          datasets: [
            {
              label: 'Tuần trước',
              fill: true,
              backgroundColor: 'rgba(100, 116, 139, .15)',
              borderColor: 'transparent',
              pointBackgroundColor: 'rgba(100, 116, 139, 1)',
              pointBorderColor: '#fff',
              pointHoverBackgroundColor: '#fff',
              pointHoverBorderColor: 'rgba(100, 116, 139, 1)',
              data: stats?.completed?.last ?? []
            },
            {
              label: 'Tuần này',
              fill: true,
              backgroundColor: 'rgba(100, 116, 139, .7)',
              borderColor: 'transparent',
              pointBackgroundColor: 'rgba(100, 116, 139, 1)',
              pointBorderColor: '#fff',
              pointHoverBackgroundColor: '#fff',
              pointHoverBorderColor: 'rgba(100, 116, 139, 1)',
              data: stats?.completed?.today ?? []
            },
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            x: {
              display: false,
              grid: {
                drawBorder: false
              }
            },
            y: {
              display: false,
              grid: {
                drawBorder: false
              }
            }
          },
          interaction: {
            intersect: false,
          },
          plugins: {
            legend: {
              labels: {
                boxHeight: 10,
                font: {
                  size: 14
                }
              }
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  return ' ' + context.parsed.y + ' phiếu hoàn thành';
                }
              }
            }
          }
        }
      });
    }

    function colors(growth) {
      if (growth > 0) {
        return {
          backgroundColor: 'rgba(101, 163, 13, .15)',
          borderColor: 'transparent',
          pointBackgroundColor: 'rgba(101, 163, 13, 1)',
          pointBorderColor: '#fff',
          pointHoverBackgroundColor: '#fff',
          pointHoverBorderColor: 'rgba(101, 163, 13, 1)',
        }
      }
      return {
        backgroundColor: 'rgba(220, 38, 38, .15)',
        borderColor: 'transparent',
        pointBackgroundColor: 'rgba(220, 38, 38, 1)',
        pointBorderColor: '#fff',
        pointHoverBackgroundColor: '#fff',
        pointHoverBorderColor: 'rgba(220, 38, 38, 1)',
      }
    }

    // Init Chart Total Orders
    if (chartTotalOrdersCon !== null) {
      chartTotalOrders = new Chart(chartTotalOrdersCon, {
        type: 'line',
        data: {
          labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'],
          datasets: [
            {
              label: 'Nhân viên mới',
              fill: true,
              ...colors(growth?.user),
              data: stats?.users || [],
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tension: .4,
          scales: {
            x: {
              display: false
            },
            y: {
              display: false
            }
          },
          interaction: {
            intersect: false,
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  return ' ' + context.parsed.y + ' nhân viên';
                }
              }
            }
          }
        }
      });
    }

    // Init Chart Total Earnings
    if (chartTotalEarningsCon !== null) {
      chartTotalEarnings = new Chart(chartTotalEarningsCon, {
        type: 'line',
        data: {
          labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'],
          datasets: [
            {
              label: 'Khách hàng mới',
              fill: true,
              ...colors(growth?.customer),
              data: stats?.customers || [],
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tension: .4,
          scales: {
            x: {
              display: false
            },
            y: {
              display: false
            }
          },
          interaction: {
            intersect: false,
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  return ' ' + context.parsed.y + ' khách hàng';
                }
              }
            }
          }
        }
      });
    }

    // Init Chart New Customers
    if (chartNewCustomersCon !== null) {
      chartNewCustomers = new Chart(chartNewCustomersCon, {
        type: 'line',
        data: {
          labels: ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'],
          datasets: [
            {
              label: 'Tổng chi phí',
              fill: true,
              ...colors(growth?.total),
              data: stats?.total || [],
            }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tension: .4,
          scales: {
            x: {
              display: false
            },
            y: {
              display: false
            }
          },
          interaction: {
            intersect: false,
          },
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                  }).format(context.parsed.y)
                }
              }
            }
          }
        }
      });
    }
  }

  /*
   * Init functionality
   *
   */
  static init() {
    this.initCharts();
  }
}

// Initialize when page loads
$(() => {
  One.onLoad(() => pageDashboard.init());
})
