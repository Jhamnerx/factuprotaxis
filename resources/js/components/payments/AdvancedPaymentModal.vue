<template>
    <el-dialog
        :visible="isOpen"
        title="Registrar Pagos Múltiples"
        :close-on-click-modal="false"
        width="700px"
        @close="closeModal"
        append-to-body
        center
    >
        <!-- Contenido del modal -->
        <div class="payment-dialog-content">
            <!-- Información del vehículo -->
            <div class="mb-4 p-3 bg-light rounded border">
                <h6 class="mb-3 text-secondary">Información del Vehículo</h6>
                <div class="row">
                    <!-- Primera fila de información -->
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label text-muted">PLACA</label>
                            <div
                                class="form-control-plaintext vehicle-info-value"
                            >
                                <h5 class="mb-0 fw-bold">
                                    {{ vehiculo ? vehiculo.placa : "" }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label text-muted"
                                >NÚMERO INTERNO</label
                            >
                            <div
                                class="form-control-plaintext vehicle-info-value"
                            >
                                <h5 class="mb-0 fw-bold">
                                    {{
                                        vehiculo
                                            ? vehiculo.numero_interno || "-"
                                            : "-"
                                    }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label text-muted"
                                >FECHA DE INGRESO</label
                            >
                            <div
                                class="form-control-plaintext vehicle-info-value"
                            >
                                <h5 class="mb-0 fw-bold">
                                    {{
                                        vehiculo
                                            ? formatDate(vehiculo.fecha_ingreso)
                                            : ""
                                    }}
                                </h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label text-muted"
                                >MESES A PAGAR</label
                            >
                            <div
                                class="form-control-plaintext vehicle-info-value"
                            >
                                <h5 class="mb-0 fw-bold">
                                    {{ selectedMonths.length }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Información de descuento si aplica -->
            <div v-if="haveDiscount" class="mb-4">
                <div class="card bg-light border-success">
                    <div class="card-body">
                        <h6 class="card-title text-success mb-2">
                            <i class="fas fa-tag me-2"></i> DESCUENTO APLICADO
                        </h6>
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="card-text mb-0">
                                    <strong>Descuento total:</strong>
                                    {{ divisa }} {{ formatCurrency(descuento) }}
                                </p>
                                <p class="card-text mb-0">
                                    <strong>Descuento por mes:</strong>
                                    {{ divisa }}
                                    {{
                                        formatCurrency(
                                            descuento / selectedMonths.length
                                        )
                                    }}
                                </p>
                                <small class="text-muted">
                                    Descuento aplicado por pago de
                                    {{ selectedMonths.length }} meses
                                </small>
                            </div>
                            <div class="text-success">
                                <h5 class="mb-0 fw-bold">
                                    {{ divisa }} {{ formatCurrency(descuento) }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Lista de meses seleccionados -->
            <div class="mb-4 p-3 bg-light rounded border">
                <h6 class="mb-3 text-secondary">Meses Seleccionados</h6>
                <div class="form-group">
                    <div class="months-container">
                        <div class="row">
                            <div class="col-12 overflow-auto">
                                <div class="d-flex flex-wrap">
                                    <div
                                        v-for="monthKey in selectedMonths"
                                        :key="monthKey"
                                        class="month-badge me-2 mb-2"
                                    >
                                        {{ formatMonthYear(monthKey) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card payment-config-card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Configuración de Pago</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Monto a pagar por mes -->
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label class="form-label">
                                    Monto por mes:
                                    <small
                                        v-if="haveDiscount"
                                        class="text-success"
                                        >(incluye descuento)</small
                                    >
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">{{
                                        divisa
                                    }}</span>
                                    <el-input-number
                                        v-model="montoPorMes"
                                        :precision="2"
                                        :step="0.01"
                                        :min="0"
                                        controls-position="right"
                                        class="flex-grow-1"
                                        @change="handleMontoPorMesChange"
                                    ></el-input-number>
                                </div>
                            </div>
                        </div>
                        <!-- Descuento -->
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label class="form-label"
                                    >Descuento Total:</label
                                >
                                <div class="input-group">
                                    <span class="input-group-text">{{
                                        divisa
                                    }}</span>
                                    <el-input-number
                                        v-model="descuento"
                                        :precision="2"
                                        :step="0.01"
                                        :min="0"
                                        controls-position="right"
                                        class="flex-grow-1"
                                        @change="handleDescuentoChange"
                                    ></el-input-number>
                                </div>
                            </div>
                        </div>
                        <!-- Monto total -->
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label class="form-label"
                                    >Monto Total a Pagar:</label
                                >
                                <div class="input-group">
                                    <span class="input-group-text">{{
                                        divisa
                                    }}</span>
                                    <el-input-number
                                        v-model="montoTotal"
                                        :precision="2"
                                        :step="0.01"
                                        :min="0"
                                        controls-position="right"
                                        class="flex-grow-1 monto-total-field"
                                        :disabled="true"
                                    ></el-input-number>
                                </div>
                            </div>
                        </div>
                        <!-- Fecha -->
                        <div class="col-md-6 mb-4">
                            <div class="form-group">
                                <label class="form-label">
                                    Fecha de pago:
                                    <small
                                        v-if="paymentTiming"
                                        class="badge bg-info text-white"
                                    >
                                        Pago a {{ paymentTiming }}
                                    </small>
                                </label>
                                <el-date-picker
                                    v-model="fecha"
                                    type="date"
                                    placeholder="Seleccione fecha"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    style="width: 100%"
                                    @change="determinarTipoPago"
                                ></el-date-picker>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selección de color -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Apariencia del Pago</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label"
                                    >Color de pagos en el calendario:</label
                                >
                                <div class="d-flex align-items-center">
                                    <el-color-picker
                                        v-model="selectedColor"
                                        :show-alpha="false"
                                        format="hex"
                                        class="me-3"
                                    ></el-color-picker>
                                    <div class="flex-grow-1">
                                        <div
                                            class="color-preview"
                                            :style="{
                                                backgroundColor: selectedColor
                                            }"
                                        ></div>
                                        <small class="text-muted mt-2 d-block">
                                            Color seleccionado:
                                            {{ selectedColor }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <template #footer>
            <div class="dialog-footer">
                <el-button @click="closeModal">Cancelar</el-button>
                <el-button
                    type="primary"
                    @click="savePagos"
                    :loading="isSubmitting"
                >
                    Guardar Pagos Múltiples
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script>
export default {
    name: "AdvancedPaymentModal",
    props: {
        isOpen: {
            type: Boolean,
            required: true
        },
        vehiculo: {
            type: Object,
            default: null
        },
        selectedMonths: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            montoPorMes: 25.0,
            montoTotal: 0,
            fecha: this.getCurrentDate(),
            selectedColor: "#34d399", // Verde por defecto
            isSubmitting: false,
            descuento: 0,
            haveDiscount: false,
            paymentTiming: "", // inicio, fin
            monthNames: {
                1: "Enero",
                2: "Febrero",
                3: "Marzo",
                4: "Abril",
                5: "Mayo",
                6: "Junio",
                7: "Julio",
                8: "Agosto",
                9: "Septiembre",
                10: "Octubre",
                11: "Noviembre",
                12: "Diciembre"
            }
        };
    },
    mounted() {
        // Al montar el componente, ya no esperamos por datos del padre
        // sino que calculamos nosotros mismos el saldo y color
        if (this.isOpen && this.vehiculo) {
            this.initialize();
        }

        // Mantenemos este evento por compatibilidad, pero ahora el modal es autónomo
        this.$root.$on(
            "init-advanced-payment-modal",
            this.initializeWithCalculatedData
        );
    },

    beforeDestroy() {
        // Limpiar los listeners para evitar memory leaks
        this.$root.$off(
            "init-advanced-payment-modal",
            this.initializeWithCalculatedData
        );
    },
    computed: {
        divisa() {
            // Obtener la divisa del plan del vehículo si está disponible
            if (
                this.vehiculo &&
                this.vehiculo.subscription &&
                this.vehiculo.subscription.plan &&
                this.vehiculo.subscription.plan.currency
            ) {
                return this.vehiculo.subscription.plan.currency;
            }
            // Si no está disponible, devolver valor por defecto
            return "PEN";
        }
    },
    watch: {
        isOpen(value) {
            if (value) {
                this.initialize();
            }
        },
        vehiculo: {
            handler(newValue) {
                if (newValue && this.isOpen) {
                    this.calcularSaldoYColor();
                }
            },
            deep: true
        },
        // Observar cambios en la fecha para recalcular descuentos
        fecha() {
            this.determinarTipoPago();
            this.calcularSaldoYColor();
        },
        // Observar cambios en los meses seleccionados
        selectedMonths: {
            handler() {
                if (this.isOpen && this.vehiculo) {
                    this.calcularSaldoYColor();
                }
            },
            deep: true
        }
    },
    methods: {
        initialize() {
            this.fecha = this.getCurrentDate();
            this.selectedColor = "#dc2626"; // Rojo por defecto para pagos múltiples
            this.determinarTipoPago();
            this.calcularSaldoYColor();
        },

        /**
         * Inicializa el modal con los datos calculados desde el componente padre
         * (Mantenemos este método por compatibilidad, pero el modal ahora calcula sus propios valores)
         * @param {Object} data - Datos calculados
         */ initializeWithCalculatedData(data) {
            // Si recibimos datos externos, podemos usarlos, pero ya no son necesarios
            if (data) {
                if (data.montoTotal !== undefined)
                    this.montoTotal = data.montoTotal;
                if (data.montoPorMes !== undefined)
                    this.montoPorMes = data.montoPorMes;
                if (data.color) this.selectedColor = data.color;
                if (data.descuento !== undefined)
                    this.descuento = data.descuento;
                if (data.haveDiscount !== undefined)
                    this.haveDiscount = data.haveDiscount;
            }

            // Recalcular el tipo de pago según la fecha actual
            this.determinarTipoPago();
        },
        /**
         * Calcula el saldo a pagar y los descuentos basado en el plan del vehículo
         * Esta función ahora sigue exactamente la lógica del código PHP original
         */ calcularSaldoYColor() {
            if (
                !this.vehiculo ||
                !this.vehiculo.subscription ||
                !this.vehiculo.subscription.plan ||
                !this.selectedMonths ||
                this.selectedMonths.length === 0
            ) {
                this.montoPorMes = 25.0;
                this.montoTotal =
                    this.montoPorMes *
                    (this.selectedMonths ? this.selectedMonths.length : 1);
                this.descuento = 0;
                this.haveDiscount = false;
                return;
            }

            const subscription = this.vehiculo.subscription;
            const plan = subscription.plan; // Precio base por mes según el plan
            this.montoPorMes = plan.price || 25.0;

            // Calcular total a pagar exactamente como en el código PHP
            let totalAPagar = this.montoPorMes * this.selectedMonths.length;

            // Por defecto, inicializar sin descuento
            this.haveDiscount = false;
            this.descuento = 0; // Verificar si hay descuentos por cantidad de meses
            if (plan.discounts && plan.discounts.length > 0) {
                // Buscar un descuento que coincida con la cantidad de meses seleccionados
                for (const discount of plan.discounts) {
                    if (discount.months === this.selectedMonths.length) {
                        this.haveDiscount = true;
                        this.descuento = discount.value || 0;
                        break;
                    }
                }
            } // Aplicar descuento si existe, exactamente como el código PHP
            if (this.haveDiscount) {
                totalAPagar -= this.descuento;

                // Calcular y almacenar el monto por mes después de aplicar descuento
                if (this.selectedMonths.length > 0) {
                    this.montoPorMes = parseFloat(
                        (totalAPagar / this.selectedMonths.length).toFixed(2)
                    );
                }
            } else {
                // Si no hay descuento, el monto por mes es el precio del plan
                this.montoPorMes = plan.price || 25.0;
            }

            // Asignar el monto total calculado
            this.montoTotal = totalAPagar;

            // Color rojo por defecto para pagos múltiples, exactamente como el código PHP
            this.selectedColor = "#dc2626";
        },
        /**
         * Calcula el monto total basado en precio por mes y descuento
         * Siguiendo exactamente la lógica del código PHP
         */ calcularMontoTotal() {
            // Al modificar manualmente el monto por mes, debemos recalcular el total
            // sin volver a recalcular el montoPorMes para evitar un ciclo
            if (this.selectedMonths.length > 0) {
                // Calcular el nuevo total basado en el monto por mes
                let subtotal =
                    parseFloat(this.montoPorMes) * this.selectedMonths.length;

                // El descuento ya está considerado en el montoPorMes, no lo restamos nuevamente
                this.montoTotal = subtotal;

                // Recalcular el descuento basado en el precio del plan
                if (
                    this.vehiculo &&
                    this.vehiculo.subscription &&
                    this.vehiculo.subscription.plan
                ) {
                    const planPrice =
                        this.vehiculo.subscription.plan.price || 25.0;
                    const totalSinDescuento =
                        planPrice * this.selectedMonths.length;
                    this.descuento = Math.max(
                        0,
                        totalSinDescuento - this.montoTotal
                    );
                    this.haveDiscount = this.descuento > 0;
                }
            } else {
                this.montoTotal = 0;
            }
        },

        /**
         * Determina si el pago es al inicio o fin de mes según la fecha seleccionada
         */
        determinarTipoPago() {
            if (!this.fecha) return;

            const fecha = new Date(this.fecha);
            const dia = fecha.getDate();
            const ultimoDiaMes = new Date(
                fecha.getFullYear(),
                fecha.getMonth() + 1,
                0
            ).getDate();

            if (dia <= 5) {
                this.paymentTiming = "inicio de mes";
                // Ya no llamamos a aplicarDescuentoInicioDeMes() aquí
                // porque ahora calcularSaldoYColor() maneja todos los descuentos
            } else if (dia >= ultimoDiaMes - 1) {
                this.paymentTiming = "fin de mes";
            } else {
                this.paymentTiming = "mitad de mes";
            }

            // Recalcular los montos según la nueva fecha
            this.calcularSaldoYColor();
        },

        getCurrentDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, "0");
            const day = String(today.getDate()).padStart(2, "0");
            return `${year}-${month}-${day}`;
        },

        formatDate(date) {
            if (!date) return "";
            const d = new Date(date);
            const day = String(d.getDate()).padStart(2, "0");
            const month = String(d.getMonth() + 1).padStart(2, "0");
            const year = d.getFullYear();
            return `${day}/${month}/${year}`;
        },

        formatMonthYear(monthKey) {
            // Formato "año-mes" a texto legible "Mes Año"
            const [year, month] = monthKey.split("-").map(Number);
            return `${this.monthNames[month]} ${year}`;
        },

        formatCurrency(value) {
            return parseFloat(value).toFixed(2);
        },

        closeModal() {
            this.$emit("close");
        },
        savePagos() {
            // Validar que los montos sean correctos antes de proceder
            if (!this.montoPorMes || this.montoPorMes <= 0) {
                this.$message.error("El monto por mes debe ser mayor a 0");
                return;
            }

            if (!this.montoTotal || this.montoTotal <= 0) {
                this.$message.error("El monto total debe ser mayor a 0");
                return;
            } // No necesitamos validar diferencias entre montos
            // ya que lo manejamos en los métodos handleMontoPorMesChange y handleDescuentoChange

            // Solo hacemos una validación final más permisiva para casos extremos
            if (
                this.vehiculo &&
                this.vehiculo.subscription &&
                this.vehiculo.subscription.plan
            ) {
                const planPrice = this.vehiculo.subscription.plan.price || 25.0;
                const minEsperado =
                    planPrice * this.selectedMonths.length * 0.2; // Mínimo 20% del precio del plan
                const maxEsperado =
                    planPrice * this.selectedMonths.length * 2.0; // Máximo 200% del precio del plan

                // Solo alertamos si el precio está extremadamente fuera del rango esperado
                if (
                    this.montoTotal < minEsperado ||
                    this.montoTotal > maxEsperado
                ) {
                    this.$confirm(
                        `El monto total ${this.divisa} ${
                            this.montoTotal
                        } parece inusual para ${
                            this.selectedMonths.length
                        } meses (valor esperado: ${this.divisa} ${(
                            planPrice * this.selectedMonths.length
                        ).toFixed(2)}). ¿Desea continuar?`,
                        "Aviso",
                        {
                            confirmButtonText: "Continuar",
                            cancelButtonText: "Cancelar",
                            type: "warning"
                        }
                    ).catch(() => {
                        return; // Cancelar si el usuario no confirma
                    });
                }
            }

            this.isSubmitting = true; // Crear objeto de pagos múltiples
            const pagos = this.selectedMonths.map(monthKey => {
                const [year, month] = monthKey.split("-").map(Number); // Asegurarnos de que todos los valores sean números y estén correctamente formateados
                const montoTotal = parseFloat(
                    parseFloat(this.montoTotal).toFixed(2)
                );
                const montoPorMes = parseFloat(
                    parseFloat(this.montoPorMes).toFixed(2)
                );
                const descuento = parseFloat(
                    parseFloat(this.descuento).toFixed(2)
                );

                // Calcular descuento por mes (dividido entre los meses)
                const descuentoPorMes =
                    this.selectedMonths.length > 0
                        ? parseFloat(
                              (descuento / this.selectedMonths.length).toFixed(
                                  2
                              )
                          )
                        : 0;
                return {
                    vehiculoId: this.vehiculo ? this.vehiculo.id : null,
                    year: year,
                    mes: month,
                    monto: montoPorMes, // Monto individual por mes
                    montoPorMes: montoPorMes, // Monto individual por mes (para lógica interna)
                    montoTotal: montoTotal, // Monto total de todos los meses
                    descuento: descuento, // Descuento total aplicado
                    descuentoPorMes: descuentoPorMes, // Descuento por mes (para lógica interna)
                    fecha: this.fecha,
                    color: this.selectedColor,
                    tipo: this.vehiculo.subscription.plan.type || "normal",
                    paymentTiming: this.paymentTiming
                };
            });

            // Simular llamada a API con un pequeño retraso

            this.isSubmitting = false;
            this.$emit("save", pagos);
            this.closeModal();
        },

        /**
         * Maneja cambios en el monto por mes y recalcula el monto total
         */
        handleMontoPorMesChange(value) {
            if (this.selectedMonths.length > 0) {
                // Cuando el usuario modifica el monto por mes, actualizamos el monto total
                this.montoTotal = parseFloat(
                    (value * this.selectedMonths.length).toFixed(2)
                );

                // Y recalculamos el descuento basado en el precio del plan
                if (
                    this.vehiculo &&
                    this.vehiculo.subscription &&
                    this.vehiculo.subscription.plan
                ) {
                    const planPrice =
                        this.vehiculo.subscription.plan.price || 25.0;
                    const totalSinDescuento =
                        planPrice * this.selectedMonths.length;
                    const nuevoDescuento = Math.max(
                        0,
                        totalSinDescuento - this.montoTotal
                    );

                    // Solo actualizamos el descuento si ha cambiado significativamente
                    if (Math.abs(nuevoDescuento - this.descuento) > 0.01) {
                        this.descuento = parseFloat(nuevoDescuento.toFixed(2));
                        this.haveDiscount = this.descuento > 0;
                    }
                }
            }
        },

        /**
         * Maneja cambios en el descuento y recalcula el monto total y monto por mes
         */
        handleDescuentoChange(value) {
            if (this.selectedMonths.length > 0) {
                // Cuando cambia el descuento, recalculamos basado en el precio del plan
                if (
                    this.vehiculo &&
                    this.vehiculo.subscription &&
                    this.vehiculo.subscription.plan
                ) {
                    const planPrice =
                        this.vehiculo.subscription.plan.price || 25.0;
                    const totalSinDescuento =
                        planPrice * this.selectedMonths.length;

                    // Actualizamos el monto total basado en el nuevo descuento
                    this.montoTotal = parseFloat(
                        (totalSinDescuento - value).toFixed(2)
                    );

                    // Y recalculamos el monto por mes
                    this.montoPorMes = parseFloat(
                        (this.montoTotal / this.selectedMonths.length).toFixed(
                            2
                        )
                    );

                    // Actualizamos el flag de descuento
                    this.haveDiscount = value > 0;
                }
            }
        }
    }
};
</script>

<style scoped>
/* Estilos para el modal de pago avanzado */
.payment-dialog-content {
    max-width: 100%;
}

.form-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control-plaintext {
    padding: 0.5rem 0;
    border-bottom: 1px solid #eaeaea;
}

.vehicle-info-value {
    min-height: 40px;
    display: flex;
    align-items: center;
}

.months-container {
    margin-top: 10px;
    padding: 5px;
    background-color: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.overflow-auto {
    overflow-x: auto;
    padding-bottom: 10px;
    max-height: 120px;
}

.month-badge {
    padding: 8px 15px;
    background-color: #007bff;
    color: white;
    border-radius: 4px;
    font-size: 0.9rem;
    white-space: nowrap;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

.month-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
}

.color-preview {
    width: 100%;
    height: 40px;
    border-radius: 6px;
    border: 1px solid #dcdfe6;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.color-preview:hover {
    border-color: #409eff;
}

.currency-prefix {
    font-weight: 600;
    font-size: 1rem;
    color: #495057;
    min-width: 40px;
    text-align: center;
}

/* Estilo para tarjetas */
.card {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border: 1px solid #eaeaea;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.card-header {
    background-color: #f8f9fa;
    padding: 1rem;
    border-bottom: 1px solid #eaeaea;
}

.card-title {
    font-weight: 600;
    margin-bottom: 0;
    color: #343a40;
}

.payment-config-card {
    border-top: 3px solid #007bff;
}

.border-success {
    border-width: 1px;
    border-color: #28a745 !important;
    border-left: 4px solid #28a745;
}

.card-body {
    padding: 1.25rem;
}

/* Estilo para los campos de formulario */
.form-control,
.input-group-text {
    border-radius: 5px;
}

.input-group {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border-radius: 5px;
}

.input-group-text {
    background-color: #f8f9fa;
    font-weight: 600;
    min-width: 45px;
    justify-content: center;
}

.form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
}

/* Mejoras para los componentes de Element UI */
:deep(.el-input-number) {
    width: 100%;
}

:deep(.el-input-number .el-input__inner) {
    text-align: left;
    height: 40px;
    font-size: 1rem;
}

:deep(.el-date-editor.el-input) {
    width: 100%;
}

:deep(.el-date-editor .el-input__inner) {
    height: 40px;
}

:deep(.el-color-picker) {
    width: auto;
    display: block;
}

:deep(.el-color-picker__trigger) {
    width: 40px;
    height: 40px;
    border-radius: 6px;
}

/* Estilo para el pie del diálogo */
.dialog-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

/* Mejora en la visualización del diálogo */
:deep(.el-dialog) {
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

:deep(.el-dialog__header) {
    background-color: #f8f9fa;
    border-bottom: 1px solid #eaeaea;
    padding: 16px 20px;
}

:deep(.el-dialog__body) {
    padding: 20px;
}

:deep(.el-dialog__title) {
    font-weight: 700;
    font-size: 1.2rem;
    color: #343a40;
}

:deep(.el-dialog__footer) {
    border-top: 1px solid #eaeaea;
    padding: 16px 20px;
    background-color: #f8f9fa;
}

:deep(.el-button) {
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: 600;
}

:deep(.el-button--primary) {
    background-color: #007bff;
    border-color: #007bff;
}

:deep(.el-button--primary:hover) {
    background-color: #0069d9;
    border-color: #0062cc;
}

/* Estilo para que el texto en el campo deshabilitado sea negro */
.monto-total-field.el-input-number.is-disabled .el-input__inner {
    color: #000 !important;
    background-color: #f8f9fa;
    font-weight: 700;
    font-size: 1.1rem;
}
</style>
