package com.sena.veterinaria;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.webkit.WebChromeClient;
import android.webkit.WebResourceError;
import android.webkit.WebResourceRequest;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

public class MainActivity extends AppCompatActivity {

    // Configuración de URL del servidor
    // Para emulador de Android Studio: "http://10.0.2.2/veterinaria"
    // Para teléfono físico en la misma red Wi-Fi: "http://10.1.174.177/veterinaria"
    private static final String DEFAULT_URL = "http://10.0.2.2/veterinaria";
    private static final String WIFI_URL = "http://10.1.174.177/veterinaria";

    private WebView webView;
    private SwipeRefreshLayout swipeRefresh;
    private ProgressBar progressBar;
    private LinearLayout layoutError;
    private Button btnReintentar;

    @SuppressLint("SetJavaScriptEnabled")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        webView = findViewById(R.id.webView);
        swipeRefresh = findViewById(R.id.swipeRefresh);
        progressBar = findViewById(R.id.progressBar);
        layoutError = findViewById(R.id.layoutError);
        btnReintentar = findViewById(R.id.btnReintentar);

        // Configuración del WebView
        WebSettings webSettings = webView.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webSettings.setDomStorageEnabled(true);
        webSettings.setDatabaseEnabled(true);
        webSettings.setUseWideViewPort(true);
        webSettings.setLoadWithOverviewMode(true);
        webSettings.setSupportZoom(false);
        webSettings.setBuiltInZoomControls(false);

        // Cliente para interceptar navegación y enlaces a WhatsApp
        webView.setWebViewClient(new WebViewClient() {
            @Override
            public boolean shouldOverrideUrlLoading(WebView view, WebResourceRequest request) {
                String url = request.getUrl().toString();

                // Si el enlace es de WhatsApp o tel, abrir con la aplicación nativa de Android
                if (url.startsWith("https://api.whatsapp.com") || url.startsWith("whatsapp:") || url.startsWith("tel:")) {
                    try {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                        startActivity(intent);
                        return true;
                    } catch (Exception e) {
                        Toast.makeText(MainActivity.this, "No se encontró la aplicación requerida", Toast.LENGTH_SHORT).show();
                        return true;
                    }
                }

                // Cargar páginas internas dentro del WebView
                view.loadUrl(url);
                return true;
            }

            @Override
            public void onPageStarted(WebView view, String url, Bitmap favicon) {
                super.onPageStarted(view, url, favicon);
                layoutError.setVisibility(View.GONE);
                progressBar.setVisibility(View.VISIBLE);
            }

            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);
                progressBar.setVisibility(View.GONE);
                swipeRefresh.setRefreshing(false);
            }

            @Override
            public void onReceivedError(WebView view, WebResourceRequest request, WebResourceError error) {
                super.onReceivedError(view, request, error);
                if (request.isForMainFrame()) {
                    layoutError.setVisibility(View.VISIBLE);
                    progressBar.setVisibility(View.GONE);
                }
            }
        });

        // Barra de progreso interactiva
        webView.setWebChromeClient(new WebChromeClient() {
            @Override
            public void progressChanged(WebView view, int newProgress) {
                if (newProgress < 100) {
                    progressBar.setVisibility(View.VISIBLE);
                    progressBar.setProgress(newProgress);
                } else {
                    progressBar.setVisibility(View.GONE);
                }
            }
        });

        // Swipe para recargar la página (Pull to refresh)
        swipeRefresh.setOnRefreshListener(() -> webView.reload());

        // Botón de reintentar si se pierde conexión
        btnReintentar.setOnClickListener(v -> {
            layoutError.setVisibility(View.GONE);
            cargarUrlInicial();
        });

        cargarUrlInicial();
    }

    private void cargarUrlInicial() {
        // Primero intenta con la IP WiFi local, y si estás en emulador o falla puedes alternar
        webView.loadUrl(WIFI_URL);
    }

    // Manejar botón Atrás de Android para navegar por el historial del WebView
    @Override
    public void onBackPressed() {
        if (webView.canGoBack()) {
            webView.goBack();
        } else {
            super.onBackPressed();
        }
    }
}
