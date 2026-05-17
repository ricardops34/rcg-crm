import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { PoModule, PoChartType, PoChartSerie } from '@po-ui/ng-components';
import { AnalyticsService } from '../../../services/analytics';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, PoModule],
  templateUrl: './dashboard.html'
})
export class DashboardComponent implements OnInit {

  summary: any = { goal: 0, realized: 0, achievement: 0 };
  categorySeries: Array<PoChartSerie> = [];
  chartType: PoChartType = PoChartType.Donut;
  isLoading: boolean = true;

  constructor(private analyticsService: AnalyticsService) { }

  ngOnInit(): void {
    this.loadDashboard();
  }

  loadData() {
    this.loadDashboard();
  }

  loadDashboard() {
    this.isLoading = true;
    this.analyticsService.getDashboardData().subscribe({
      next: (res) => {
        this.summary = res.summary;
        this.categorySeries = res.categories;
        this.isLoading = false;
      },
      error: () => {
        this.isLoading = false;
      }
    });
  }

}
