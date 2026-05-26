import { Component, OnInit, inject } from "@angular/core";
import { CommonModule } from "@angular/common";
import { FormsModule } from "@angular/forms";
import {
  PoModule,
  PoNotificationService,
  PoPageAction
} from "@po-ui/ng-components";
import { AuthService } from "../../../services/auth";
import { AuthUser } from "../../../services/models/auth.model";

@Component({
  selector: "app-profile",
  standalone: true,
  imports: [CommonModule, PoModule, FormsModule],
  templateUrl: "./profile.html"
})
export class ProfileComponent implements OnInit {
  private authService = inject(AuthService);
  private poNotification = inject(PoNotificationService);

  user: Partial<AuthUser> = {};
  isLoading: boolean = true;
  passwordData: { current: string; new: string; confirm: string } = {
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
    const updateData: Partial<AuthUser> & { password?: string } = {
      name: this.user.name,
      email: this.user.email,
      avatar: this.user.avatar
    };

    if (this.passwordData.new) {
      updateData.password = this.passwordData.new;
    }

    this.authService.updateProfile(updateData).subscribe({
      next: (updatedUser) => {
        this.poNotification.success("Perfil atualizado com sucesso!");
        this.user = { ...updatedUser };
        this.passwordData = { current: "", new: "", confirm: "" };
        this.isLoading = false;
      },
      error: () => {
        this.poNotification.error("Erro ao atualizar perfil.");
        this.isLoading = false;
      }
    });
  }

  onUpload(event: Event | { file: Blob }) {
    const file = "file" in event ? event.file : null;
    if (!file) {
      return;
    }

    const reader = new FileReader();
    reader.onload = (loadEvent: ProgressEvent<FileReader>) => {
      this.user.avatar = loadEvent.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
}
