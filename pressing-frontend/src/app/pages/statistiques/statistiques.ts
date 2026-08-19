import { Component, OnInit, signal } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { BaseChartDirective } from 'ng2-charts';
import { ChartData, ChartOptions } from 'chart.js';
import { Stats, DailyStats } from '../../services/stats';

@Component({
  selector: 'app-statistiques',
  standalone: true,
  imports: [CommonModule, RouterLink, BaseChartDirective],
  templateUrl: './statistiques.html',
  styleUrl: './statistiques.css'
})
export class Statistiques implements OnInit {
  daily = signal<DailyStats | null>(null);
  loading = signal(true);

  ticketsPerMonthData = signal<ChartData<'bar'>>({ labels: [], datasets: [{ data: [], label: 'Tickets par mois' }] });
  revenuePerServiceData = signal<ChartData<'pie'>>({ labels: [], datasets: [{ data: [] }] });

  chartOptions: ChartOptions = { responsive: true };

  constructor(private statsService: Stats) {}

  ngOnInit(): void {
    this.statsService.getDaily().subscribe({
      next: (data) => {
        this.daily.set(data);
        this.loading.set(false);
      },
      error: () => this.loading.set(false)
    });

    this.statsService.getTicketsPerMonth().subscribe({
      next: (data) => {
        this.ticketsPerMonthData.set({
          labels: data.map(d => d.month),
          datasets: [{ data: data.map(d => d.total), label: 'Tickets par mois' }]
        });
      }
    });

    this.statsService.getRevenuePerService().subscribe({
      next: (data) => {
        const totalsByService: Record<string, number> = {};
        data.forEach(d => {
          totalsByService[d.service] = (totalsByService[d.service] || 0) + parseFloat(d.total);
        });

        this.revenuePerServiceData.set({
          labels: Object.keys(totalsByService),
          datasets: [{ data: Object.values(totalsByService) }]
        });
      }
    });
  }
}