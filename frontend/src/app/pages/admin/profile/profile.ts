import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";
import { 
  PoModule, 
  PoNotificationService, 
  PoPageAction 
} from "@po-ui/ng-components";
import { AuthService } from "../../../services/auth";
import { ClienteService } from "../../../services/cliente"; // Reaproveitando para exemplo se necessário

@Component({
  selector: "app-profile",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./profile.html"
})
export class ProfileComponent implements OnInit {
  private authService = inject(AuthService);
  private poNotification = inject(PoNotificationService);

  user: any = {};
  isLoading: boolean = true;
  passwordData: any = {
    current: "",
    new: "",
    confirm: ""
  };

  readonly actions: Array<PoPageAction> = [
    { label: "Salvar Alterações", action: this.saveProfile.bind(this), icon: "po-icon-ok" }
  ];

  ngOnInit() {
    this.loadProfile();
  }

  loadProfile() {
    this.isLoading = true;
    const userData = this.authService.getUser();
    if (userData) {
      this.user = { ...userData };
    }
    this.isLoading = false;
  }

  saveProfile() {
    if (this.passwordData.new && this.passwordData.new !== this.passwordData.confirm) {
      this.poNotification.error("As senhas não conferem.");
      return;
    }

    this.isLoading = true;
    const updateData: any = {
      name: this.user.name,
      email: this.user.email,
      avatar: this.user.avatar
    };

    if (this.passwordData.new) {
      updateData.password = this.passwordData.new;
    }

    this.authService.updateProfile(updateData).subscribe({
      next: () => {
        this.poNotification.success("Perfil atualizado com sucesso!");
        // Atualizar localStorage para o menu superior refletir a mudança
        const currentUser = this.authService.getUser();
        this.authService.handleAuthResponse({ 
          accessToken: localStorage.getItem("token"), 
          user: { ...currentUser, ...updateData } 
        });
        
        this.passwordData = { current: "", new: "", confirm: "" };
        this.isLoading = false;
      },
      error: () => {
        this.poNotification.error("Erro ao atualizar perfil.");
        this.isLoading = false;
      }
    });
  }

  onUpload(event: any) {
    const file = event.file;
    const reader = new FileReader();
    reader.onload = (e: any) => {
      this.user.avatar = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}
