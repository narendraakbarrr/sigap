import 'package:flutter/material.dart';
import 'app_colors.dart';
import 'app_typography.dart';

/// ThemeData terpusat untuk aplikasi SIGAP.
class AppTheme {
  AppTheme._();

  static const double radiusSm = 8;
  static const double radiusMd = 12;
  static const double radiusLg = 16;

  static ColorScheme get _colorScheme => const ColorScheme(
        brightness: Brightness.light,
        primary: AppColors.primaryBlue,
        onPrimary: AppColors.white,
        primaryContainer: AppColors.primaryBlueLight,
        onPrimaryContainer: AppColors.primaryBlueDark,
        secondary: AppColors.successGreen,
        onSecondary: AppColors.white,
        secondaryContainer: AppColors.successGreenLight,
        onSecondaryContainer: AppColors.successGreenDark,
        tertiary: AppColors.urgentOrange,
        onTertiary: AppColors.white,
        tertiaryContainer: AppColors.urgentOrangeLight,
        onTertiaryContainer: AppColors.urgentOrangeDark,
        error: AppColors.dangerRed,
        onError: AppColors.white,
        errorContainer: AppColors.dangerRedLight,
        onErrorContainer: AppColors.dangerRedDark,
        surface: AppColors.white,
        onSurface: AppColors.ink900,
        surfaceContainerHighest: AppColors.slate100,
        onSurfaceVariant: AppColors.slate600,
        outline: AppColors.slate200,
        outlineVariant: AppColors.slate100,
        shadow: Colors.black,
        scrim: Colors.black,
        inverseSurface: AppColors.ink900,
        onInverseSurface: AppColors.white,
        inversePrimary: AppColors.primaryBlueLight,
        surfaceTint: AppColors.primaryBlue,
      );

  static ThemeData get light {
    final colorScheme = _colorScheme;
    final textTheme = AppTypography.textTheme;

    return ThemeData(
      useMaterial3: true,
      colorScheme: colorScheme,
      scaffoldBackgroundColor: AppColors.white,
      textTheme: textTheme,
      fontFamily: textTheme.bodyMedium?.fontFamily,

      // -----------------------------------------------------------------
      // AppBar — biru solid, tegas, memberi kesan "instansi resmi"
      // -----------------------------------------------------------------
      appBarTheme: AppBarTheme(
        backgroundColor: AppColors.primaryBlue,
        foregroundColor: AppColors.white,
        elevation: 0,
        centerTitle: true,
        surfaceTintColor: Colors.transparent,
        titleTextStyle: textTheme.titleLarge?.copyWith(color: AppColors.white),
        iconTheme: const IconThemeData(color: AppColors.white),
      ),

      // -----------------------------------------------------------------
      // Tombol
      // -----------------------------------------------------------------
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: AppColors.primaryBlue,
          foregroundColor: AppColors.white,
          disabledBackgroundColor: AppColors.slate200,
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(radiusMd),
          ),
          textStyle: textTheme.labelLarge,
          elevation: 0,
        ),
      ),
      outlinedButtonTheme: OutlinedButtonThemeData(
        style: OutlinedButton.styleFrom(
          foregroundColor: AppColors.primaryBlue,
          side: const BorderSide(color: AppColors.primaryBlue, width: 1.5),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(radiusMd),
          ),
          textStyle: textTheme.labelLarge?.copyWith(color: AppColors.primaryBlue),
        ),
      ),
      textButtonTheme: TextButtonThemeData(
        style: TextButton.styleFrom(
          foregroundColor: AppColors.primaryBlue,
          textStyle: textTheme.labelLarge?.copyWith(color: AppColors.primaryBlue),
        ),
      ),

      // -----------------------------------------------------------------
      // Input (form laporan, login, register)
      // -----------------------------------------------------------------
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: AppColors.slate100,
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        hintStyle: textTheme.bodyMedium?.copyWith(color: AppColors.slate400),
        labelStyle: textTheme.bodyMedium,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(radiusMd),
          borderSide: BorderSide.none,
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(radiusMd),
          borderSide: BorderSide.none,
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(radiusMd),
          borderSide: const BorderSide(color: AppColors.primaryBlue, width: 1.5),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(radiusMd),
          borderSide: const BorderSide(color: AppColors.dangerRed, width: 1.5),
        ),
        focusedErrorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(radiusMd),
          borderSide: const BorderSide(color: AppColors.dangerRed, width: 1.5),
        ),
      ),

      // -----------------------------------------------------------------
      // Card — dipakai di report_list & dashboard
      // -----------------------------------------------------------------
      cardTheme: CardThemeData(
        color: AppColors.white,
        elevation: 0,
        surfaceTintColor: Colors.transparent,
        margin: EdgeInsets.zero,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(radiusLg),
          side: const BorderSide(color: AppColors.slate200, width: 1),
        ),
      ),

      // -----------------------------------------------------------------
      // Chip — filter kategori (jalan rusak, lampu mati, saluran air, dst.)
      // -----------------------------------------------------------------
      chipTheme: ChipThemeData(
        backgroundColor: AppColors.slate100,
        selectedColor: AppColors.primaryBlueLight,
        disabledColor: AppColors.slate100,
        labelStyle: textTheme.labelMedium,
        secondaryLabelStyle: textTheme.labelMedium?.copyWith(color: AppColors.primaryBlueDark),
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(999),
          side: BorderSide.none,
        ),
      ),

      // -----------------------------------------------------------------
      // FAB — tombol "Lapor" baru, oranye supaya menonjol (urgensi/aksi utama)
      // -----------------------------------------------------------------
      floatingActionButtonTheme: const FloatingActionButtonThemeData(
        backgroundColor: AppColors.urgentOrange,
        foregroundColor: AppColors.white,
        elevation: 2,
      ),

      // -----------------------------------------------------------------
      // Bottom navigation
      // -----------------------------------------------------------------
      bottomNavigationBarTheme: BottomNavigationBarThemeData(
        backgroundColor: AppColors.white,
        selectedItemColor: AppColors.primaryBlue,
        unselectedItemColor: AppColors.slate400,
        selectedLabelStyle: textTheme.labelMedium,
        unselectedLabelStyle: textTheme.labelMedium,
        type: BottomNavigationBarType.fixed,
        elevation: 8,
      ),

      dividerTheme: const DividerThemeData(
        color: AppColors.slate200,
        thickness: 1,
        space: 1,
      ),

      snackBarTheme: SnackBarThemeData(
        backgroundColor: AppColors.ink900,
        contentTextStyle: textTheme.bodyMedium?.copyWith(color: AppColors.white),
        behavior: SnackBarBehavior.floating,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(radiusSm),
        ),
      ),

      dialogTheme: DialogThemeData(
        backgroundColor: AppColors.white,
        surfaceTintColor: Colors.transparent,
        titleTextStyle: textTheme.titleLarge,
        contentTextStyle: textTheme.bodyMedium,
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(radiusLg),
        ),
      ),
    );
  }
}